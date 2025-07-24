import vue from 'eslint-plugin-vue';
import prettier from 'eslint-plugin-prettier';
import vueParser from 'vue-eslint-parser';

export default [
  {
    files: ['**/*.vue'],
    languageOptions: {
      ecmaVersion: 2021,
      sourceType: 'module',
      parser: vueParser,
    },
    plugins: {
      vue,
      prettier,
    },
    rules: {
      'semi': ['error', 'always'],
    },
  },
  {
    files: ['**/*.js'],
    languageOptions: {
      ecmaVersion: 2021,
      sourceType: 'module',
    },
    rules: {
      // regras JS
    },
  },
];