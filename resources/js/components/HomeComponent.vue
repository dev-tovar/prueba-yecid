<template>
  <div class="container">
    <v-card>
      <v-card-title class="primary--text d-block px-9">
        <h3 class="font-weight-bold mt-2">Gestión de obras literarias</h3>
      </v-card-title>
      <v-card-text>
        <v-data-table
          :loading="loading_items_libros"
          v-model="libro_seleccion"
          single-select
          show-select
          :headers="headers"
          :items="items_libros"
          item-key="isbn"
          class="elevation-0 custom-table"
          :footer-props="{
            'items-per-page-text': 'Libros por página',
            pageText: '{0}-{1} de {2}',
          }"
        >
          <template v-slot:top>
            <v-card-title>
              <v-responsive class="pt-2" max-width="400">
                <v-text-field
                  v-model="buscar_libro"
                  placeholder="Buscar..."
                  dense
                  outlined
                  hide-details
                  rounded
                  append-icon="mdi-magnify"
                  @click:append="getLibros"
                  @keydown.enter="getLibros"
                ></v-text-field>
              </v-responsive>
              <v-spacer></v-spacer>

              <v-tooltip color="secondary" top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    v-bind="attrs"
                    v-on="on"
                    :disabled="libro_seleccion.length == 0 ? true : false"
                    color="red"
                    icon
                    class="mr-3"
                    @click="confirmDeleteLibro"
                    :class="
                      libro_seleccion.length == 1
                        ? 'animate__animated animate__bounceIn'
                        : null
                    "
                  >
                    <v-icon> mdi-delete </v-icon>
                  </v-btn>
                </template>
                <span>Eliminar Libro</span>
              </v-tooltip>

              <v-tooltip color="secondary" top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    v-bind="attrs"
                    v-on="on"
                    @click="editarLibro"
                    outlined
                    :disabled="libro_seleccion.length == 0 ? true : false"
                    color="primary"
                    icon
                    class="mr-3"
                    :class="
                      libro_seleccion.length == 1
                        ? 'animate__animated animate__bounceIn'
                        : null
                    "
                  >
                    <v-icon> mdi-pencil-plus </v-icon>
                  </v-btn>
                </template>
                <span>Editar Libro</span>
              </v-tooltip>

              <v-tooltip color="secondary" top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    v-bind="attrs"
                    v-on="on"
                    @click="getLibros"
                    outlined
                    color="secondary"
                    icon
                    class="mr-9"
                  >
                    <v-icon> mdi-reload </v-icon>
                  </v-btn>
                </template>
                <span>Actualizar listado de libros</span>
              </v-tooltip>
              <v-btn
                @click="abrirDialogLibro('nuevo')"
                color="primary"
                depressed
                rounded
                class="text-capitalize"
              >
                <v-icon left>mdi-plus</v-icon> Crear Nuevo
              </v-btn>
            </v-card-title>
          </template>
          <template v-slot:item.titulo="{ item }">
            <a @click="verInfoLibro(item)" href="javascript:;">{{
              item.titulo
            }}</a>
          </template>
          <template v-slot:item.autores="{ item }">
            <v-chip v-if="item.autores.length > 0" small>{{
              item.autores[0].nombre_completo
            }}</v-chip>
            <span class="grey--text text-caption">{{
              item.autores.length > 1
                ? "(+" + (item.autores.length - 1) + ")"
                : null
            }}</span>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>

    <v-dialog v-model="dialog_libro" scrollable max-width="800px" persistent>
      <v-card>
        <v-card-title class="font-weight-bold"
          >{{ dialog_libro_titulo }}
          <v-spacer></v-spacer>
          <v-btn @click="dialog_libro = false" icon smal class="grey lighten-3">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-divider class="my-0"></v-divider>
        <v-card-text>
          <v-form ref="form_libro">
            <v-row wrap class="ma-0 py-5">
              <v-col cols="12" md="12" sm="12" class="py-1">
                <span class="font-weight-bold">Título:</span>
                <v-text-field
                  :rules="[rules_form.requerido, rules_form.maximo45caracteres]"
                  v-model="form_libro.titulo"
                  class="mt-1"
                  dense
                  outlined
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6" sm="12" class="py-1">
                <span class="font-weight-bold">ISBN:</span>
                <v-text-field
                :disabled="dialog_libro_titulo == 'Editar Libro' ? true: false "
                  :rules="[
                    rules_form.requerido,
                    rules_form.maximo45caracteres,
                    rules_form.numeros,
                  ]"
                  v-model="form_libro.isbn"
                  class="mt-1"
                  dense
                  outlined
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6" sm="12" class="py-1">
                <span class="font-weight-bold">Editorial:</span>
                <v-autocomplete
                  :rules="[rules_form.requerido]"
                  attach
                  :items="items_editorial"
                  item-text="nombre"
                  item-value="id"
                  v-model="form_libro.editorial"
                  class="mt-1"
                  dense
                  outlined
                  prepend-inner-icon="mdi-refresh"
                  @click:prepend-inner="getItemsEditoriales"
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" md="12" sm="12" class="py-1">
                <span class="font-weight-bold">Sinopsis:</span>
                <v-textarea
                  :rules="[rules_form.requerido]"
                  v-model="form_libro.sinopsis"
                  class="mt-1"
                  rows="3"
                  outlined
                ></v-textarea>
              </v-col>
              <v-col cols="12" md="6" sm="12" class="py-1">
                <span class="font-weight-bold">Autores:</span>
                <v-autocomplete
                prepend-inner-icon="mdi-refresh"
                  @click:prepend-inner="getItemsAutores"
                  :rules="[rules_form.algun_valor_seleccionado]"
                  v-model="form_libro.autores"
                  :items="items_autores"
                  item-text="nombre_completo"
                  item-value="id"
                  multiple
                  class="mt-1"
                  dense
                  outlined
                >
                  <template v-slot:selection="{ item, index }">
                    <v-chip v-if="index === 0">
                      <span>{{ item.nombre_completo }}</span>
                    </v-chip>
                    <span v-if="index === 1" class="grey--text text-caption">
                      (+{{ form_libro.autores.length - 1 }}
                      {{ form_libro.autores.length > 2 ? "autores" : "autor" }})
                    </span>
                  </template>
                </v-autocomplete>
              </v-col>
              <v-col cols="12" md="6" sm="12" class="py-1">
                <span class="font-weight-bold">Número de páginas:</span>
                <v-text-field
                  :rules="[
                    rules_form.requerido,
                    rules_form.maximo45caracteres,
                    rules_form.numeros,
                  ]"
                  v-model="form_libro.numero_paginas"
                  class="mt-1"
                  dense
                  outlined
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-divider
          v-if="dialog_libro_titulo != 'Información del Libro'"
          class="my-0"
        ></v-divider>
        <v-card-actions v-if="dialog_libro_titulo != 'Información del Libro'">
          <v-spacer></v-spacer>
          <v-btn
            :disabled="loading_form_save"
            class="text-capitalize"
            rounded
            depressed
            color="secondary"
            text
            @click="dialog_libro = false"
          >
            Cancelar
          </v-btn>
          <v-btn
            :loading="loading_form_save"
            :disabled="loading_form_save"
            v-if="form_libro.new"
            class="text-capitalize"
            rounded
            depressed
            color="primary"
            @click="guardarLibro"
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
            @click="actualizarLibro"
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
      v-if="libro_seleccion[0]"
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
          ¿Desea eliminar el libro?
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
          Esta a punto de eliminar el libro "<span class="font-weight-bold">{{
            libro_seleccion[0].titulo
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
            @click="deleteLibro"
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
    <v-snackbar class="animate__animated animate__bounceInDown" top right color="red" v-model="snackbar_error" v-if="error_save && error_save.errors">
      <ul>
        <template v-for="(error, key1) in error_save.errors">
          <li v-for="(item, key2) in error" :key="key1 + key2">
            {{ item }}
          </li>
        </template>
      </ul>

      <template v-slot:action="{ attrs }">
        <v-btn color="white"  outlined class="text-capitalize" v-bind="attrs" @click="snackbar_error = false">
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
      libro_seleccion: [],
      items_editorial: [],
      items_autores: [],
      dialog_confirm_delete: false,
      dialog_response: false,
      dialog_libro: false,
      loading_form_save: false,
      loading_items_libros: false,
      dialog_libro_titulo: "Nuevo Libro",
      headers: [
        {
          text: "Obra Literaria",
          align: "start",
          sortable: false,
          value: "titulo",
          class: "text-body-1 font-weight-bold",
        },
        {
          text: "ISBN",
          value: "isbn",
          class: "text-body-1 font-weight-bold",
        },
        {
          text: "Editorial",
          value: "editorial.nombre",
          class: "text-body-1 font-weight-bold",
        },
        {
          text: "Autores",
          value: "autores",
          class: "text-body-1 font-weight-bold",
        },
        {
          text: "Número de páginas",
          value: "n_paginas",
          class: "text-body-1 font-weight-bold",
        },
        {
          text: "Fecha de creación",
          value: "created_at",
          class: "text-body-1 font-weight-bold",
        },
      ],
      items_libros: [],
      form_libro: {
        isbn: null,
        titulo: null,
        editorial: null,
        sinopsis: null,
        autores: [],
        numero_paginas: null,
        new: true,
      },
      buscar_libro: null,
      snackbar_error: false,
      error_save: null,
    };
  },
  mounted() {
    this.getLibros();
    this.getItemsAutores();
    this.getItemsEditoriales();
  },
  methods: {
    verInfoLibro(libro) {
      this.form_libro.isbn = libro.isbn;
      this.form_libro.titulo = libro.titulo;
      this.form_libro.editorial = libro.id_editorial;
      this.form_libro.sinopsis = libro.sinopsis;
      if (libro.autores.length > 0) {
        this.form_libro.autores = libro.autores.map((a) => a.id);
      }
      this.form_libro.numero_paginas = libro.n_paginas;
      this.form_libro.new = false;
      this.abrirDialogLibro("ver");
    },
    getLibros() {
      this.libro_seleccion = [];
      this.loading_items_libros = true;
      var buscar_libro = this.buscar_libro
        ? "?search=" + this.buscar_libro
        : "";
      axios
        .get("/crudLibros" + buscar_libro)
        .then((res) => {
          this.items_libros = res.data;
          this.loading_items_libros = false;
          // console.log(res);
        })
        .catch((err) => {
          console.error(err);
          this.loading_items_libros = false;
        });
    },
    guardarLibro() {
      if (this.$refs.form_libro.validate()) {
      this.snackbar_error = false;
      this.error_save = null;
      this.loading_form_save = true;
      axios
        .post("/crudLibros", {
          form_libro: this.form_libro,
        })
        .then((res) => {
          console.log(res);
          this.loading_form_save = false;
          this.getLibros();
          this.dialog_libro = false;
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
    actualizarLibro() {
      if (this.$refs.form_libro.validate()) {
        this.snackbar_error = false;
        this.error_save = null;
        this.loading_form_save = true;
        axios
          .put("/crudLibros/" + this.form_libro.isbn, {
            form_libro: this.form_libro,
          })
          .then((res) => {
            console.log(res);
            this.loading_form_save = false;
            this.getLibros();
            this.dialog_libro = false;
            this.dialog_response = true;
          })
          .catch((err) => {
            this.snackbar_error = true;
            console.error(err);
            this.error_save = err.response.data;
            this.loading_form_save = false;
          });
      }
    },
    deleteLibro() {
      this.loading_form_save = true;
      axios
        .delete("/crudLibros/" + this.libro_seleccion[0].isbn)
        .then((res) => {
          console.log(res);
          this.loading_form_save = false;
          this.getLibros();
          this.dialog_confirm_delete = false;
          this.libro_seleccion = [];
        })
        .catch((err) => {
          console.error(err);
          this.loading_form_save = false;
        });
    },
    getItemsAutores() {
      axios
        .get("/crudAutores")
        .then((res) => {
          this.items_autores = res.data;
          // console.log(res);
        })
        .catch((err) => {
          console.error(err);
        });
    },
    getItemsEditoriales() {
      axios
        .get("/crudEditoriales")
        .then((res) => {
          this.items_editorial = res.data;
          // console.log(res);
        })
        .catch((err) => {
          console.error(err);
        });
    },
    editarLibro() {
      if (this.libro_seleccion[0]) {
        this.form_libro.isbn = this.libro_seleccion[0].isbn.toString();
        this.form_libro.titulo = this.libro_seleccion[0].titulo;
        this.form_libro.editorial = this.libro_seleccion[0].id_editorial;
        this.form_libro.sinopsis = this.libro_seleccion[0].sinopsis;
        if (this.libro_seleccion[0].autores.length > 0) {
          this.form_libro.autores = this.libro_seleccion[0].autores.map(
            (a) => a.id
          );
        }
        this.form_libro.numero_paginas = this.libro_seleccion[0].n_paginas.toString();
        this.form_libro.new = false;
        this.abrirDialogLibro("editar");
      }
    },
    abrirDialogLibro(form) {
      if (form == "nuevo") {
        //RESETAR FORM
        this.resetFormLibro();
        this.dialog_libro_titulo = "Nuevo Libro";
      } else if (form == "editar") {
        this.dialog_libro_titulo = "Editar Libro";
      } else {
        this.dialog_libro_titulo = "Información del Libro";
      }
      this.dialog_libro = true;

      setTimeout(() => {
        this.$refs.form_libro.resetValidation();
      }, 100);
    },
    confirmDeleteLibro() {
      this.dialog_confirm_delete = true;
    },
    resetFormLibro() {
      this.form_libro = {
        isbn: null,
        titulo: null,
        editorial: null,
        sinopsis: null,
        autores: [],
        numero_paginas: null,
        new: true,
      };
    },
  },
};
</script>
