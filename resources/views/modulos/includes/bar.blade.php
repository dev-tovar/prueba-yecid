<v-app-bar
    app
    clipped-right
    flat
    height="72"
    color="white"
  >
  <v-app-bar-nav-icon @click.stop="clickToggleDrawer">
    <v-icon>@{{ drawer == false ? 'mdi-menu-open' : 'mdi-menu' }} </v-icon>
    </v-icon>
</v-app-bar-nav-icon>
    
  </v-app-bar>