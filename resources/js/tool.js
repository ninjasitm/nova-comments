Nova.booting((Vue) => {
    Vue.component("commenter", require("./components/Tool"));
    Vue.component("commenter-notes", require("./components/Notes"));
});
