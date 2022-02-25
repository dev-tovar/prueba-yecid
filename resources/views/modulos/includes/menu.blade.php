<v-navigation-drawer
app
width="350"
v-model="drawer"

>

<template v-slot:prepend>
    <v-list-item class="px-2" two-line>
      <v-list-item-avatar>
        <img src="/img/avatar.jpg">
      </v-list-item-avatar>

      <v-list-item-content>
        <v-list-item-title>Yecid Alberto Tovar</v-list-item-title>
        <v-list-item-subtitle>Desarrollador full stack</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>
  </template>

  {{-- <v-divider class="mt-0"></v-divider> --}}


<v-list
  shaped
>
  <v-list-item color="primary"
  v-for="(link, index) in itemsBar" :key="index" :to="link.ruta"
    link
  >
  <v-list-item-action class="mr-4">
    <v-icon>@{{ link . icon }}</v-icon>
</v-list-item-action>
    <v-list-item-content>
      <v-list-item-title class="caption">@{{ link . text }}</v-list-item-title>
    </v-list-item-content>
  </v-list-item>
</v-list>

<template v-slot:append>
    
     <v-list
  shaped
>
  <v-list-item
  to="/curriculum_yecid_tovar"
    link
  >
  <v-list-item-action class="mr-4">
    <v-icon>mdi-file-pdf-box</v-icon>
</v-list-item-action>
    <v-list-item-content>
      <v-list-item-title class="caption">Mi hoja de vida</v-list-item-title>
    </v-list-item-content>
  </v-list-item>
</v-list>
  </template>
</v-navigation-drawer>

