var app= new Vue({
    el:".testimonials",
    data:{
        array: [],
        
    },
    methods: {
        updateArr: function(a){
            temp = JSON.stringify(a);
            temp = JSON.parse(temp);  
            for (let i=0; i<temp.length ; i++){
                aux=temp[i].comm;
                temp[i].comm=aux.replace(/-/g," ");
            }
            this.array = temp;
        }
    }
})