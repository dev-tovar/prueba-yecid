<template>
  <div class="container">
    <v-card>
      <v-card-title class="primary--text d-block px-9">
        <h3 class="font-weight-bold mt-2">Gestión de autores</h3>
      </v-card-title>
      <v-card-text>
        <v-data-table
          :loading="loading_items_autores"
          v-model="autor_seleccion"
          single-select
          show-select
          :headers="headers"
          :items="items_autores"
          item-key="id"
          class="elevation-0 custom-table"
          :footer-props="{
            'items-per-page-text': 'Autores por página',
            pageText: '{0}-{1} de {2}',
          }"
        >
          <template v-slot:top>
            <v-card-title>
              <v-responsive class="pt-2" max-width="400">
                <v-text-field
                  v-model="buscar_autor"
                  placeholder="Buscar..."
                  dense
                  outlined
                  hide-details
                  rounded
                  append-icon="mdi-magnify"
                  @click:append="getAutores"
                  @keydown.enter="getAutores"
                ></v-text-field>
              </v-responsive>
              <v-spacer></v-spacer>

              <v-tooltip color="secondary" top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    v-bind="attrs"
                    v-on="on"
                    :disabled="autor_seleccion.length == 0 ? true : false"
                    color="red"
                    icon
                    class="mr-3"
                    @click="confirmDeleteAutor"
                    :class="
                      autor_seleccion.length == 1
                        ? 'animate__animated animate__bounceIn'
                        : null
                    "
                  >
                    <v-icon> mdi-delete </v-icon>
                  </v-btn>
                </template>
                <span>Eliminar Autor</span>
              </v-tooltip>

              <v-tooltip color="secondary" top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    v-bind="attrs"
                    v-on="on"
                    @click="editarAutor"
                    outlined
                    :disabled="autor_seleccion.length == 0 ? true : false"
                    color="primary"
                    icon
                    class="mr-3"
                    :class="
                      autor_seleccion.length == 1
                        ? 'animate__animated animate__bounceIn'
                        : null
                    "
                  >
                    <v-icon> mdi-pencil-plus </v-icon>
                  </v-btn>
                </template>
                <span>Editar Autor</span>
              </v-tooltip>

              <v-tooltip color="secondary" top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    v-bind="attrs"
                    v-on="on"
                    @click="getAutores"
                    outlined
                    color="secondary"
                    icon
                    class="mr-9"
                  >
                    <v-icon> mdi-reload </v-icon>
                  </v-btn>
                </template>
                <span>Actualizar listado de autores</span>
              </v-tooltip>
              <v-btn
                @click="abrirDialogAutor('nuevo')"
                color="primary"
                depressed
                rounded
                class="text-capitalize"
              >
                <v-icon left>mdi-plus</v-icon> Crear Nuevo
              </v-btn>
            </v-card-title>
          </template>
          <template v-slot:item.nombre="{ item }">
            <a @click="verInfoAutor(item)" href="javascript:;">{{
              item.nombre
            }}</a>
          </template>
          
        </v-data-table>
      </v-card-text>
    </v-card>

    <v-dialog v-model="dialog_autor" scrollable max-width="800px" persistent>
      <v-card>
        <v-card-title class="font-weight-bold"
          >{{ dialog_autor_titulo }}
          <v-spacer></v-spacer>
          <v-btn @click="dialog_autor = false" icon smal class="grey lighten-3">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-divider class="my-0"></v-divider>
        <v-card-text>
          <v-form ref="form_autor">
            <v-row wrap class="ma-0 py-5">
              <v-col cols="12" md="6" sm="12" class="py-1">
                <span class="font-weight-bold">Nombre:</span>
                <v-text-field
                  :rules="[rules_form.requerido, rules_form.maximo45caracteres]"
                  v-model="form_autor.nombre"
                  class="mt-1"
                  dense
                  outlined
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6" sm="12" class="py-1">
                <span class="font-weight-bold">Apellidos:</span>
                <v-text-field
                  :rules="[rules_form.requerido, rules_form.maximo45caracteres]"
                  v-model="form_autor.apellidos"
                  class="mt-1"
                  dense
                  outlined
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-divider
          v-if="dialog_autor_titulo != 'Información del Autor'"
          class="my-0"
        ></v-divider>
        <v-card-actions v-if="dialog_autor_titulo != 'Información del Autor'">
          <v-spacer></v-spacer>
          <v-btn
            :disabled="loading_form_save"
            class="text-capitalize"
            rounded
            depressed
            color="secondary"
            text
            @click="dialog_autor = false"
          >
            Cancelar
          </v-btn>
          <v-btn
            :loading="loading_form_save"
            :disabled="loading_form_save"
            v-if="form_autor.new"
            class="text-capitalize"
            rounded
            depressed
            color="primary"
            @click="guardarAutor"
          >
            <v-icon left>mdi-check</v-icon> Guardar
          </v-btn>
          <v-btn
            :loading="loading_form_save"
            :disabled="loading_form_save"
            v-else
            class="text-capitalize"
            rounded
            depressed
            color="primary"
            @click="actualizarAutor"
          >
            <v-icon left>mdi-check</v-icon> Actualizar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialog_response" persistent max-width="500">
      <v-card class="py-9">
        <v-card-text
          class="
            primary--text
            text-center
            pt-1
            pb-0
            text-h4
            ml-0
            mr-0
            mb-0
            font-weight-bold
          "
        >
          Bien hecho
        </v-card-text>
        <v-card-text
          class="
            secondary--text
            text-center
            py-0
            px-9
            text-h6
            ml-0
            mr-0
            mt-0
            mb-5
            text-break
          "
          style="line-height: 1.2"
        >
          La información se ha almacenado correctamente.
        </v-card-text>
        <v-card-actions class="d-block text-center">
          <v-btn
            @click="dialog_response = false"
            min-width="200"
            color="secondary"
            rounded
            depressed
            class="text-capitalize"
            >¡Entendido!</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="dialog_confirm_delete"
      v-if="autor_seleccion[0]"
      persistent
      max-width="600"
    >
      <v-card class="py-9">
        <v-card-text
          class="
            red--text
            text-center
            pt-1
            pb-0
            text-h4
            ml-0
            mr-0
            mb-0
            font-weight-bold
          "
        >
          ¿Desea eliminar el autor?
        </v-card-text>
        <v-card-text
          class="
            secondary--text
            text-center
            py-0
            px-9
            text-h6
            ml-0
            mr-0
            mt-0
            mb-5
            text-break
          "
          style="line-height: 1.2"
        >
          Esta a punto de eliminar el autor "<span class="font-weight-bold">{{
            autor_seleccion[0].nombre_completo
          }}</span
          >", una vez sea eliminado no habra forma de recuperar la información.
          <span class="font-weight-bold">¿desea continuar?</span>.
        </v-card-text>
        <v-card-actions class="d-block text-center">
          <v-btn
            @click="dialog_confirm_delete = false"
            min-width="200"
            color="secondary"
            outlined
            rounded
            depressed
            :loading="loading_form_save"
            :disabled="loading_form_save"
            class="text-capitalize"
            >Cancelar</v-btn
          >
          <v-btn
            @click="deleteAutor"
            min-width="200"
            color="red"
            rounded
            dark
            depressed
            :loading="loading_form_save"
            :disabled="loading_form_save"
            class="text-capitalize"
            >Si, eliminar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar
      class="animate__animated animate__bounceInDown"
      top
      right
      color="red"
      v-model="snackbar_error"
      v-if="error_save && error_save.errors"
    >
      <ul>
        <template v-for="(error, key1) in error_save.errors">
          <li v-for="(item, key2) in error" :key="key1 + key2">
            {{ item }}
          </li>
        </template>
      </ul>

      <template v-slot:action="{ attrs }">
        <v-btn
          color="white"
          outlined
          class="text-capitalize"
          v-bind="attrs"
          @click="snackbar_error = false"
        >
          Entendido
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>
<script>
import validaciones from "./validaciones/funcionesValidar";
export default {
  mixins: [validaciones],
  data() {
    return {
      autor_seleccion: [],
      dialog_confirm_delete: false,
      dialog_response: false,
      dialog_autor: false,
      loading_form_save: false,
      loading_items_autores: false,
      dialog_autor_titulo: "Nuevo Autor",
      headers: [
        {
          text: "Nombre",
          align: "start",
          sortable: false,
          value: "nombre",
          class: "text-body-1 font-weight-bold",
        },
        {
          text: "Apellidos",
          value: "apellidos",
          class: "text-body-1 font-weight-bold",
        },
        {
          text: "Libros registrados",
          value: "libros_count",
          class: "text-body-1 font-weight-bold",
        },
        {
          text: "Fecha de creación",
          value: "created_at",
          class: "text-body-1 font-weight-bold",
        },
      ],
      items_autores: [],
      form_autor: {
        id: 0,
        nombre: null,
        apellidos: null,
        new: true,
      },
      buscar_autor: null,
      snackbar_error: false,
      error_save: null,
    };
  },
  mounted() {
    this.getAutores();
  },
  methods: {
    getAutores() {
      this.autor_seleccion = [];
      this.loading_items_autores = true;
      var buscar_autor = this.buscar_autor
        ? "?search=" + this.buscar_autor
        : "";
      axios
        .get("/crudAutores" + buscar_autor)
        .then((res) => {
          this.items_autores = res.data;
          this.loading_items_autores = false;
          // console.log(res);
        })
        .catch((err) => {
          console.error(err);
          this.loading_items_autores = false;
        });
    },
    guardarAutor() {
      if (this.$refs.form_autor.validate()) {
        this.snackbar_error = false;
        this.error_save = null;
        this.loading_form_save = true;
        axios
          .post("/crudAutores", {
            form_autor: this.form_autor,
          })
          .then((res) => {
            console.log(res);
            this.loading_form_save = false;
            this.getAutores();
            this.dialog_autor = false;
            this.dialog_response = true;
          })
          .catch((err) => {
            console.error(err);
            this.snackbar_error = true;
            this.error_save = err.response.data;
            this.loading_form_save = false;
          });
      }
    },

    actualizarAutor() {
      if (this.$refs.form_autor.validate()) {
        this.snackbar_error = false;
        this.error_save = null;
        this.loading_form_save = true;
        axios
          .put("/crudAutores/" + this.form_autor.id, {
            form_autor: this.form_autor,
          })
          .then((res) => {
            console.log(res);
            this.loading_form_save = false;
            this.getAutores();
            this.dialog_autor = false;
            this.dialog_response = true;
          })
          .catch((err) => {
            this.snackbar_error = true;
            this.error_save = err.response.data;
            this.loading_form_save = false;
          });
      }
    },
    editarAutor() {
      if (this.autor_seleccion[0]) {
        this.form_autor.id = this.autor_seleccion[0].id;
        this.form_autor.nombre = this.autor_seleccion[0].nombre;
        this.form_autor.apellidos = this.autor_seleccion[0].apellidos;
        this.form_autor.new = false;
        this.abrirDialogAutor("editar");
      }
    },
    confirmDeleteAutor() {
      this.dialog_confirm_delete = true;
    },
    abrirDialogAutor(form) {
      if (form == "nuevo") {
        //RESETAR FORM
        this.resetFormAutor();
        this.dialog_autor_titulo = "Nuevo Autor";
      } else if (form == "editar") {
        this.dialog_autor_titulo = "Editar Autor";
      } else {
        this.dialog_autor_titulo = "Información del Autor";
      }
      this.dialog_autor = true;

      setTimeout(() => {
        this.$refs.form_autor.resetValidation();
      }, 100);
    },
    deleteAutor() {
      this.loading_form_save = true;
      axios
        .delete("/crudAutores/" + this.autor_seleccion[0].id)
        .then((res) => {
          console.log(res);
          this.loading_form_save = false;
          this.getAutores();
          this.dialog_confirm_delete = false;
          this.autor_seleccion = [];
        })
        .catch((err) => {
           this.snackbar_error = true;
            this.error_save = err.response.data;
            this.loading_form_save = false;
          this.loading_form_save = false;
        });
    },
     verInfoAutor(autor) {
      this.form_autor.id = autor.id;
      this.form_autor.nombre = autor.nombre;
      this.form_autor.apellidos = autor.apellidos;
      this.form_autor.new = false;
      this.abrirDialogAutor("ver");
    },
    resetFormAutor() {
      this.form_autor = {
        id: 0,
        nombre: null,
        apellidos: null,
        new: true,
      };
    },
  },
};
</script>