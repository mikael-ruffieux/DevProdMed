<script>
//const escapeRegex = str => str.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
//article.contenu.match(new RegExp(escapeRegex(filterBy.value), 'ui'))

import {ref, computed} from 'vue';

export default {
  props: {
    'articles': {},
    'limit': {type: Number, default: 10}
  },

  setup(props) {
    const filterBy = ref('');
    const articlesFiltered = computed(() => props.articles.filter(
      article => article.contenu.toLowerCase().includes(filterBy.value.toLowerCase())
    ));
    const showArticles = computed(() => articlesFiltered.value.slice(0, props.limit));
    return {filterBy, showArticles};
  }
}
</script>

<template>
  <input type="text" placeholder="Rechercher" v-model="filterBy">
  <article v-for="article of showArticles" :key="article.id">
    <h1>{{ article.titre }}</h1>
    <p>{{ article.contenu }}</p>
  </article>
</template>