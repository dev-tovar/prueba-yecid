export default {
    data() {
        return {
            rules_form: {
                requerido: v => !!v || "Este campo es requerido",
                algun_valor_seleccionado: v =>
                (v && v.length > 0 ) ||
                "Este campo es requerido",
                numeros: v => /^([0-9])*$/.test(v) || "Este campo debe ser númerico",
                alfanumerico: v =>
                    /^([0-9A-Za-z])*$/.test(v) ||
                    "Este campo no permite caracteres especiales",
                maximo45caracteres: v =>
                    (v && v.length <= 45) ||
                    "Este campo no debe superar los 45 caracteres",
            },
        }
    }
};
