<template>
  <div class="container">
    <v-card>
      <v-card-title class="primary--text d-block px-9">
        <h3 class="font-weight-bold mt-2">Gestión de editoriales</h3>
      </v-card-title>
      <v-card-text>
        <v-data-table
          :loading="loading_items_editoriales"
          v-model="editorial_seleccion"
          single-select
          show-select
          :headers="headers"
          :items="items_editoriales"
          item-key="id"
          class="elevation-0 custom-table"
          :footer-props="{
            'items-per-page-text': 'Editoriales por página',
            pageText: '{0}-{1} de {2}',
          }"
        >
          <template v-slot:top>
            <v-card-title>
              <v-responsive class="pt-2" max-width="400">
                <v-text-field
                  v-model="buscar_editorial"
                  placeholder="Buscar..."
                  dense
                  outlined
                  hide-details
                  rounded
                  append-icon="mdi-magnify"
                  @click:append="getEditoriales"
                  @keydown.enter="getEditoriales"
                ></v-text-field>
              </v-responsive>
              <v-spacer></v-spacer>

              <v-tooltip color="secondary" top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    v-bind="attrs"
                    v-on="on"
                    :disabled="editorial_seleccion.length == 0 ? true : false"
                    color="red"
                    icon
                    class="mr-3"
                    @click="confirmDeleteEditorial"
                    :class="
                      editorial_seleccion.length == 1
                        ? 'animate__animated animate__bounceIn'
                        : null
                    "
                  >
                    <v-icon> mdi-delete </v-icon>
                  </v-btn>
                </template>
                <span>Eliminar Editorial</span>
              </v-tooltip>

              <v-tooltip color="secondary" top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    v-bind="attrs"
                    v-on="on"
                    @click="editarEditorial"
                    outlined
                    :disabled="editorial_seleccion.length == 0 ? true : false"
                    color="primary"
                    icon
                    class="mr-3"
                    :class="
                      editorial_seleccion.length == 1
                        ? 'animate__animated animate__bounceIn'
                        : null
                    "
                  >
                    <v-icon> mdi-pencil-plus </v-icon>
                  </v-btn>
                </template>
                <span>Editar Editorial</span>
              </v-tooltip>

              <v-tooltip color="secondary" top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    v-bind="attrs"
                    v-on="on"
                    @click="getEditoriales"
                    outlined
                    color="secondary"
                    icon
                    class="mr-9"
                  >
                    <v-icon> mdi-reload </v-icon>
                  </v-btn>
                </template>
                <span>Actualizar listado de editoriales</span>
              </v-tooltip>
              <v-btn
                @click="abrirDialogEditorial('nuevo')"
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
            <a @click="verInfoEditorial(item)" href="javascript:;">{{
              item.nombre
            }}</a>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>

    <v-dialog v-model="dialog_editorial" scrollable max-width="800px" persistent>
      <v-card>
        <v-card-title class="font-weight-bold"
          >{{ dialog_editorial_titulo }}
          <v-spacer></v-spacer>
          <v-btn @click="dialog_editorial = false" icon smal class="grey lighten-3">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-divider class="my-0"></v-divider>
        <v-card-text>
          <v-form ref="form_editorial">
            <v-row wrap class="ma-0 py-5">
              <v-col cols="12" md="6" sm="12" class="py-1">
                <span class="font-weight-bold">Nombre:</span>
                <v-text-field
                  :rules="[rules_form.requerido, rules_form.maximo45caracteres]"
                  v-model="form_editorial.nombre"
                  class="mt-1"
                  dense
                  outlined
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6" sm="12" class="py-1">
                <span class="font-weight-bold">Sede:</span>
                <v-text-field
                  :rules="[rules_form.requerido, rules_form.maximo45caracteres]"
                  v-model="form_editorial.sede"
                  class="mt-1"
                  dense
                  outlined
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-divider
          v-if="dialog_editorial_titulo != 'Información del Editorial'"
          class="my-0"
        ></v-divider>
        <v-card-actions v-if="dialog_editorial_titulo != 'Información del Editorial'">
          <v-spacer></v-spacer>
          <v-btn
            :disabled="loading_form_save"
            class="text-capitalize"
            rounded
            depressed
            color="secondary"
            text
            @click="dialog_editorial = false"
          >
            Cancelar
          </v-btn>
          <v-btn
            :loading="loading_form_save"
            :disabled="loading_form_save"
            v-if="form_editorial.new"
            class="text-capitalize"
            rounded
            depressed
            color="primary"
            @click="guardarEditorial"
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
            @click="actualizarEditorial"
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
      v-if="editorial_seleccion[0]"
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
          ¿Desea eliminar la editorial?
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
          Esta a punto de eliminar el editorial "<span class="font-weight-bold">{{
            editorial_seleccion[0].nombre
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
            @click="deleteEditorial"
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
      editorial_seleccion: [],
      dialog_confirm_delete: false,
      dialog_response: false,
      dialog_editorial: false,
      loading_form_save: false,
      loading_items_editoriales: false,
      dialog_editorial_titulo: "Nuevo Editorial",
      headers: [
        {
          text: "Nombre",
          align: "start",
          sortable: false,
          value: "nombre",
          class: "text-body-1 font-weight-bold",
        },
        {
          text: "Sede",
          value: "sede",
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
      items_editoriales: [],
      form_editorial: {
        id: 0,
        nombre: null,
        sede: null,
        new: true,
      },
      buscar_editorial: null,
      snackbar_error: false,
      error_save: null,
    };
  },
  mounted() {
    this.getEditoriales();
  },
  methods: {
    getEditoriales() {
      this.editorial_seleccion = [];
      this.loading_items_editoriales = true;
      var buscar_editorial = this.buscar_editorial
        ? "?search=" + this.buscar_editorial
        : "";
      axios
        .get("/crudEditoriales" + buscar_editorial)
        .then((res) => {
          this.items_editoriales = res.data;
          this.loading_items_editoriales = false;
          // console.log(res);
        })
        .catch((err) => {
          console.error(err);
          this.loading_items_editoriales = false;
        });
    },
    guardarEditorial() {
      if (this.$refs.form_editorial.validate()) {
        this.snackbar_error = false;
        this.error_save = null;
        this.loading_form_save = true;
        axios
          .post("/crudEditoriales", {
            form_editorial: this.form_editorial,
          })
          .then((res) => {
            console.log(res);
            this.loading_form_save = false;
            this.getEditoriales();
            this.dialog_editorial = false;
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

    actualizarEditorial() {
      if (this.$refs.form_editorial.validate()) {
        this.snackbar_error = false;
        this.error_save = null;
        this.loading_form_save = true;
        axios
          .put("/crudEditoriales/" + this.form_editorial.id, {
            form_editorial: this.form_editorial,
          })
          .then((res) => {
            console.log(res);
            this.loading_form_save = false;
            this.getEditoriales();
            this.dialog_editorial = false;
            this.dialog_response = true;
          })
          .catch((err) => {
            this.snackbar_error = true;
            this.error_save = err.response.data;
            this.loading_form_save = false;
          });
      }
    },
    editarEditorial() {
      if (this.editorial_seleccion[0]) {
        this.form_editorial.id = this.editorial_seleccion[0].id;
        this.form_editorial.nombre = this.editorial_seleccion[0].nombre;
        this.form_editorial.sede = this.editorial_seleccion[0].sede;
        this.form_editorial.new = false;
        this.abrirDialogEditorial("editar");
      }
    },
    confirmDeleteEditorial() {
      this.dialog_confirm_delete = true;
    },
    abrirDialogEditorial(form) {
      if (form == "nuevo") {
        //RESETAR FORM
        this.resetFormEditorial();
        this.dialog_editorial_titulo = "Nuevo Editorial";
      } else if (form == "editar") {
        this.dialog_editorial_titulo = "Editar Editorial";
      } else {
        this.dialog_editorial_titulo = "Información del Editorial";
      }
      this.dialog_editorial = true;

      setTimeout(() => {
        this.$refs.form_editorial.resetValidation();
      }, 100);
    },
    deleteEditorial() {
      this.loading_form_save = true;
      axios
        .delete("/crudEditoriales/" + this.editorial_seleccion[0].id)
        .then((res) => {
          console.log(res);
          this.loading_form_save = false;
          this.getEditoriales();
          this.dialog_confirm_delete = false;
          this.editorial_seleccion = [];
        })
        .catch((err) => {
           this.snackbar_error = true;
            this.error_save = err.response.data;
            this.loading_form_save = false;
          this.loading_form_save = false;
        });
    },
     verInfoEditorial(editorial) {
      this.form_editorial.id = editorial.id;
      this.form_editorial.nombre = editorial.nombre;
      this.form_editorial.sede = editorial.sede;
      this.form_editorial.new = false;
      this.abrirDialogEditorial("ver");
    },
    resetFormEditorial() {
      this.form_editorial = {
        id: 0,
        nombre: null,
        sede: null,
        new: true,
      };
    },
  },
};
</script>