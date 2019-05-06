Structurally this plugin is a thin wrapper that glues JavaScript based highlighting onto a label-
like activity.



Adding highlightjs
------------------

Highlight.js comes in a ready-to-go AMD format even if it isn't obvious.

Build steps I took:
1. Head to download hljs: https://highlightjs.org/download/

2. I took the stock 'common' languages plus ones I wanted, ones useful for Moodle-style development,
    namely: Gherkin, Handlebars, SCSS and YAML.

3. The resulting download includes a highlights.pack.js file, this was renamed and put into its
    respective locations. I have not tried to unminify the file (and don't care that codechecker
    is upset by this fact)

4. I took the one style I wanted from the styles/ folder, namely monokai-sublime.css, prefixed all
    of the styles with .modtype_codesnippet prefixes and bundled this into the plugin's styles.css.


Adding highlightjs-line-numbers
-------------------------------

I also wanted line-numbers support. To facilitate that, I used the plugin for Highlightjs:
    https://github.com/wcoder/highlightjs-line-numbers.js

Build steps:
1. Wrapped the entire code inside an AMD module, in a quick and dirty fashion which basically
    amounts to wrapping the AMD define statement around it and encapsulating it all in an object
    so it can return a function in that object called 'init' which is just what would run in
    any other situation.

2. Added a call to w.hljs.initLineNumbersOnLoad(); just after the checks against w.hljs were
    made so it will actually initialise.

3. After that, adding some styles to styles.css to include the formatting there too.

4. Then the usual grunt step to compile the JS.
