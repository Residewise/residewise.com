<template>
  <div class="locale-switcher">
    <b-nav-item-dropdown
      v-model="$i18n.locale"
      :text="$i18n.locale.toUpperCase()"
      right
    >
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
