
# Plugin Mateus Ávila Isidoro

This plugin is part of the application process to work with Awesome Motive.



## Stack

**Front-end:** Vue 3.3, using: vite, pinia, UnoCSS, Typescript, maska, Sass, date-fns, vue chart.js, vue router, zod and @vorms.

**Back-end:** Wordpress, using: WP Rest API, transients, options, translations.


## Functionalities

### API

- [x]  Translation to English and Brazilian Portuguese. 
- [x]  Shared language object between WP and Vue.
- [x]  Shared nonce between WP and Vue.
- [x]  All routes require authenticated access.
- [x]  Route for updating the settings (using the PUT method).
- [x]  Settings are saved using options.
- [x]  Route for retrieving all the settings (using the GET method).
- [x]  Implemented a transient of 1 hour to enhance backend performance.
- [x]  Implemented checks to avoid saving duplicate emails.
- [x]  Implemented checks to avoid saving incorrect emails.
- [x]  Sanitized the human_date_format and rows values.
- [x]  Converted graph data to an array instead of an object.
- [x]  Typed all backend responses.
- [x]  Ensured that the plugin applies JS and CSS code only within its scope.

### Settings page

- [x]  Rows only accept numbers from 1 to 5. A custom mask has been created to prevent errors.
- [x]  Implemented a toggle box to provide better control over the checkbox.
- [x]  The form does not allow the submission of duplicate emails.
- [x]  Pinia is updated every time the form is successfully submitted, even on the /data route.
- [x]  Upon the initial load of the plugin, Pinia takes full control of the data.
- [x]  Provided translated error messages for enhanced user experience.
- [x]  After sending the request, a modal appears to indicate that the request is processing.
- [x]  The "Add" button is disabled when the list contains five emails.
- [x]  The "Minus" button is disabled when the list contains only one email.
- [x]  Sanitized the human_date_format and rows values.
- [x]  The user's email is shared between WP and Vue.

### Graphic page

- [x]  Converted graph data to an array for easier implementation.
- [x]  Converted dates to a human-readable format if the user selects this option.
- [x]  Implemented an animation for the Chart.js when the page loads.

### Home page

- [x]  The table displays the specified number of rows.
- [x]  Translated the table header to enhance the user experience.
- [x]  Listed all emails in an unordered list.


### Extras

- [x]  Created composables to enhance code quality.
- [x]  Provided translated message for users who have JavaScript blocked.
- [x]  Utilized HTML5 Dialog in the modal, which can be cancelled with the Escape button.
- [x]  Typed all props and emits in components.
- [x]  Typed the translation as well.
## Contribute

Clone the repository

```bash
  git clone git@github.com:mateusavila/mateus-avila-isidoro.git
```

Go to the front-end

```bash
  cd mateus-avila-isidoro
```

Install all the dependencies

```bash
  pnpm install
```

Start the development server to run inside Wordpress

```bash
  npm run develop
```

