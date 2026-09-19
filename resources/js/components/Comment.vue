<template>
    <div class="commenter__comment py-4 border-t border-40">
        <div class="font-light text-80 text-sm">
            <template v-if="hasCommenter">
                <a class="no-underline dim text-primary font-bold" :href="commenterUrl" v-text="commenter"></a>

                said
            </template>

            <template v-else>
                Written
            </template>

            {{ date }}
        </div>

        <div class="mt-2" v-html="commentString"></div>
    </div>
</template>

<script>
    export default {
        props: {
            comment: {
                type: Object,
                required: true
            }
        },

        computed: {
            commentString() {
                return this.field('comment')?.value ?? ''
            },

            commenter() {
                return this.field('commenter')?.value ?? ''
            },

            commenterUrl() {
                const commenter = this.field('commenter')

                return commenter?.belongsToId && commenter.resourceName
                    ? Nova.url(`/resources/${commenter.resourceName}/${commenter.belongsToId}`)
                    : null
            },

            date() {
                const date = new Date(this.field('created_at')?.value)

                return Number.isNaN(date.getTime()) ? '' : `on ${new Intl.DateTimeFormat(undefined, {
                    dateStyle: 'medium',
                    timeStyle: 'short',
                }).format(date)}`
            },

            hasCommenter() {
                return Boolean(this.commenter);
            }
        },

        methods: {
            field(attribute) {
                return this.comment.fields.find((field) => field.attribute === attribute)
            }
        }
    }
</script>
