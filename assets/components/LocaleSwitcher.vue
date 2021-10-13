<template>
  <div class="locale-switcher">
    <b-nav-item-dropdown
        no-caret
      v-model="$i18n.locale"
      right
    >
      <template #text>
        <span class="mdi mdi-earth"></span> {{ $i18n.locale.toUpperCase() }}
      </template>
      <b-dropdown-item @click="$i18n.locale = 'en'" href="#"
        >English</b-dropdown-item
      >
      <b-dropdown-item @click="$i18n.locale = 'cz'" href="#"
        >Czech</b-dropdown-item
      >
      <b-dropdown-item @click="$i18n.locale = 'ru'" href="#"
        >Russian</b-dropdown-item
      >
    </b-nav-item-dropdown>
  </div>
</template>

<script>
export default {};
</script>

<style scoped></style>
