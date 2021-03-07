<template>
  <div>
    <h4 class="text-90 font-normal text-2xl mb-3">
      Notes
    </h4>

    <div class="card mb-6 overflow-hidden">
      <div class="flex border-b border-40 remove-bottom-border px-8">
        <div class="w-full pt-6 pb-2">
          <h4 class="font-normal text-80">
            Write new note
          </h4>
          <select-control
            v-model="type"
            :options="[
            {
                label: 'Note',
                value: 'note'
            },
            {
                label: 'Innapropriate',
                value: 'innapropriate'
            }
          ]"
          />
          <textarea
            class="w-full form-control form-input form-input-bordered py-3 h-auto mt-2"
            id="user"
            dusk="user"
            rows="5"
            v-model="note"
            @keyup.enter.93="createNote"
          >
        </textarea>
        </div>
      </div>

      <div class="flex justify-between px-8 pb-4 border-b border-40">
        <div class="help-text">
          On MacOS, press ⌘ + Enter to save
        </div>

        <button
          class="btn btn-default btn-primary inline-flex items-center relative mt-4"
          type="submit"
          @click="createNote"
        >
          Save Note
        </button>
      </div>

      <div
        class="flex border-b border-40 remove-bottom-border px-8"
        v-if="hasNotes"
      >
        <div class="w-full py-6">
          <h3 class="text-90 font-bold text-lg mb-4">Notes</h3>

          <comment-note
            v-for="(note, key) in data.resources"
            :note="note"
            :key="key"
          ></comment-note>
        </div>
      </div>

      <div
        class="bg-20 rounded-b"
        v-if="hasPagination"
      >
        <nav class="flex justify-between items-center">
          <button
            class="btn btn-link py-3 px-4"
            :class="paginationClass(hasNextLink)"
            :disabled="! hasNextLink"
            @click="getNotes(data.next_page_url)"
          >
            Older
          </button>

          <button
            class="btn btn-link py-3 px-4"
            :class="paginationClass(hasPrevLink)"
            :disabled="! hasPrevLink"
            @click="getNotes(data.prev_page_url)"
          >
            Newer
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import SelectControl from "../../../vendor/laravel/nova/resources/js/components/Controls/SelectControl.vue";
import CommentNote from "./CommentNote";

export default {
  props: {
    resourceName: {
      type: String,
      default: "\\KirschbaumDevelopment\\NovaComments\\Nova\\Note",
    },
    resourceId: [Number, String],
    field: [Object],
  },

  components: { CommentNote, SelectControl },

  data() {
    return {
      baseNoteUri: "/nova-api/comment-notes",
      note: "",
      type: "note",
      data: {
        next_page_url: "",
        prev_page_url: "",
        resources: {},
      },
    };
  },

  mounted() {
    this.getNotes(this.notesUri);
  },

  computed: {
    notesUri() {
      return `${this.baseNoteUri}?page=1`;
    },

    hasNotes() {
      return Boolean(this.data.resources.length);
    },

    hasNextLink() {
      return Boolean(this.data.next_page_url);
    },

    hasPrevLink() {
      return Boolean(this.data.prev_page_url);
    },

    hasPagination() {
      return this.hasNextLink || this.hasPrevLink;
    },

    queryParams() {
      return `&orderBy=created_at&orderByDirection=desc&viaResource=${this.resourceName}&viaResourceId=${this.resourceId}&viaRelationship=notes&relationshipType=hasMany`;
    },
  },

  methods: {
    createNote() {
      if (!this.note) {
        return false;
      }

      let payload = {
        content: this.note,
        type: this.type,
        viaResource: this.resourceName,
        viaResourceId: this.resourceId,
        viaRelationship: "notes",
      };

      axios
        .post(this.baseNoteUri, payload)
        .then(() => {
          this.getNotes(this.notesUri);

          this.resetNote();

          this.$toasted.show(`A new note has been created.`, {
            type: "success",
          });
        })
        .catch((response) => this.$toasted.show(response, { type: "error" }));
    },

    getNotes(uri) {
      axios
        .get(`${uri}${this.queryParams}`)
        .then(({ data }) => (this.data = data));
    },

    paginationClass(isActive) {
      return isActive ? "text-primary dim" : "text-80 opacity-50";
    },

    resetNote() {
      this.note = "";
    },
  },
};
</script>
