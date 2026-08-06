import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';
import { RouterLink } from '@angular/router';


interface Avocat {

id_avocat:number;

utilisateur:{
nom:string;
prenom:string;
region:string;
};


profil:{
specialites:string|null;
disponibilite:string;
}|null;


avis_avg_note:number|null;

avis_count:number;

score_bayesien?:number;

}



@Component({

selector:'app-recherche-avocat',

standalone:true,

imports:[
CommonModule,
FormsModule,
RouterLink
],

templateUrl:'./recherche-avocat.html',

styleUrl:'./recherche-avocat.scss'

})


export class RechercheAvocat implements OnInit {


avocats=signal<Avocat[]>([]);


loading=signal(true);


query='';


region='';



regions=[

'Dakar',
'Diourbel',
'Fatick',
'Kaffrine',
'Kaolack',
'Kédougou',
'Kolda',
'Louga',
'Matam',
'Saint-Louis',
'Sédhiou',
'Tambacounda',
'Thiès',
'Ziguinchor'

];



dispoLabels:Record<string,string>={

disponible:'Disponible',

en_pause:'En pause',

en_vacances:'En vacances'

};



constructor(private api:Api){}



ngOnInit(){

this.loadAvocats();

}



loadAvocats(){


this.loading.set(true);



let params:string[]=[];



if(this.query.trim()){

params.push(

`q=${encodeURIComponent(this.query)}`

);

}



if(this.region){

params.push(

`region=${encodeURIComponent(this.region)}`

);

}



const url=params.length

? `/api/citoyen/avocats?${params.join('&')}`

: '/api/citoyen/avocats';



this.api.get<Avocat[]>(url)

.subscribe({

next:data=>{

this.avocats.set(data);

this.loading.set(false);

},


error:()=>{

this.loading.set(false);

}

});


}


}