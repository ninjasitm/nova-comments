<template>
  <div class="commenter__comment py-4 border-t border-40">
    <div class="flex font-light text-80 text-sm">
      <div class="col">
        <template v-if="hasCommenter">
          <a
            class="no-underline dim text-primary font-bold"
            :href="commenterUrl"
            v-text="commenter"
          ></a>

          said
        </template>

        <template v-else>
          Written
        </template>

        {{ date }}
      </div>
      <div class="col">
        <button
          type="button"
          @keydown.enter.prevent="showNotes = !showNotes"
          @click.prevent="showNotes = !showNotes"
          tabindex="0"
          class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"
        >
          <icon
            type="view"
            view-box="0 0 20 20"
            width="16"
            height="16"
          /> Notes
        </button>
      </div>
    </div>

    <div
      class="mt-2"
      v-html="commentString"
    ></div>
    <comment-notes
      v-if="showNotes"
      :resource-id="comment.id"
    />
  </div>
</template>

<script>
// require('moment-timezone');

export default {
  props: {
    comment: {
      type: Object,
      required: true,
    },
  },

  computed: {
    commentString() {
      return _.find(this.comment.fields, { attribute: "comment" }).value;
    },

    commenter() {
      return _.find(this.comment.fields, { attribute: "commenter" }).value;
    },

    commenterUrl() {
      let commenterId = _.find(this.comment.fields, { attribute: "commenter" })
        .belongsToId;

      return `/nova/resources/users/${commenterId}`;
    },

    date() {
      let now = moment();
      let date = moment
        .utc(_.find(this.comment.fields, { attribute: "created_at" }).value)
        .tz(moment.tz.guess());

      if (date.isSame(now, "minute")) {
        return "just now";
      }

      if (date.isSame(now, "day")) {
        return `at ${date.format("LT")}`;
      }

      if (date.isSame(now, "year")) {
        return `on ${date.format("MMM D")}`;
      }

      return `on ${date.format("ll")}`;
    },

    hasCommenter() {
      return Boolean(this.commenter);
    },
  },
};
</script>
