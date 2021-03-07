<?php

namespace KirschbaumDevelopment\NovaComments\Nova;

use Laravel\Nova\Panel;
use Laravel\Nova\Resource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Whitecube\NovaFlexibleContent\Flexible;
use KirschbaumDevelopment\NovaNotes\CommentNotes;
use KirschbaumDevelopment\NovaComments\Models\Comment as CommentModel;

class Comment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = CommentModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'comment',
    ];

    /**
     * Create a new resource instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
        Comment::$model = config('nova-comment.comment-class', static::$model);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        $fields = [
            Textarea::make('Comment')
                ->alwaysShow()
                ->hideFromIndex(),

            MorphTo::make('Commentable')->onlyOnIndex(),

            Text::make('Comment')
                ->displayUsing(function ($comment) {
                    return Str::limit($comment, config('nova-comments.limit'));
                })
                ->onlyOnIndex(),

            BelongsTo::make('Commenter', 'commenter', config('nova-comments.commenter.nova-resource'))
                ->exceptOnForms(),

            DateTime::make('Created', 'created_at')
                ->format(config('nova-comments.date-format'))
                ->exceptOnForms()
                ->sortable(),
        ];

        if (config('nova-comments.supports-approval')) {
            array_unshift($fields, Boolean::make("Is Approved"));
        }

        if (config('nova-comments.supports-reported-comments')) {
            array_push($fields,  new Panel('Moderation', $this->moderationPanel()));
        }

        return $fields;
    }

    protected function moderationPanel()
    {
        $fields = [
            new CommentNotes("Notes")
        ];
        return $fields;
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return config('nova-comments.available-for-navigation');
    }
}