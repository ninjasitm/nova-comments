<template>
  <div class="user__note py-4 border-t border-40">
    <div class="font-light text-80 text-sm">
      <span :class="{
            label: true,
            'label-error label-danger': ['inappropriate'].indexOf(noteType.toLowerCase()) > -1
        }">{{ noteType }}</span>
      <template v-if="hasUser">
        <a
          class="no-underline dim text-primary font-bold"
          :href="userUrl"
          v-text="user"
        ></a>

        said
      </template>

      <template v-else>
        Written
      </template>

      {{ date }}
    </div>

    <div
      class="mt-2"
      v-html="noteString"
    ></div>
  </div>
</template>

<script>
// require('moment-timezone');

export default {
  props: {
    note: {
      type: Object,
      required: true,
    },
  },

  computed: {
    noteString() {
      return _.find(this.note.fields, { attribute: "content" }).value;
    },
    noteType() {
      return _.find(this.note.fields, { attribute: "type" }).value || "note";
    },

    user() {
      return _.find(this.note.fields, { attribute: "user" }).value;
    },

    userUrl() {
      let userId = _.find(this.note.fields, { attribute: "user" }).belongsToId;

      return `/nova/resources/users/${userId}`;
    },

    date() {
      let now = moment();
      let date = moment
        .utc(_.find(this.note.fields, { attribute: "created_at" }).value)
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

    hasUser() {
      return Boolean(this.user);
    },
  },
};
</script>
