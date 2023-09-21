var app = new Vue({
    el: ".results",
    data: {
        risultato: '',
        punteggio: '',
        n: 0
    },
    methods: {
        update : function(p){
            document.getElementById("ris").style.display="none";
            document.getElementById("hide").style.display="block";
            this.n = p;
            this.punteggio= p + '/10';   
        }
    },
});
