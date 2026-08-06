import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';

interface Dossier {
id_dossier:number;
motif:string;
description:string;
budget:number|null;
niveau_urgence:number;
citoyen:{
utilisateur:{
nom:string;
prenom:string;
region:string;
}
};
}

@Component({
selector:'app-avocat-recherche-dossiers',
standalone:true,
imports:[CommonModule,FormsModule],
templateUrl:'./avocat-recherche-dossiers.html',
styleUrl:'./avocat-recherche-dossiers.scss',
})

export class AvocatRechercheDossiers implements OnInit{

dossiers=signal<Dossier[]>([]);
loading=signal(true);

query='';
urgenceFiltre='';

urgenceLabels:Record<number,string>={
1:'Faible',
2:'Normale',
3:'Urgente',
4:'Critique'
};

urgenceColors:Record<number,string>={
1:'#2F855A',
2:'#3B5A7A',
3:'#D69E2E',
4:'#C0392B'
};


proposalOpenFor=signal<number|null>(null);

proposalMessage='';

proposalSent=signal<Record<number,string>>({});

proposalError=signal<Record<number,string>>({});


constructor(private api:Api){}


ngOnInit(){
this.load();
}


load(){

this.loading.set(true);

let path='/api/avocat/dossiers-disponibles';

const params:string[]=[];


if(this.query){
params.push(`q=${encodeURIComponent(this.query)}`);
}


if(this.urgenceFiltre){
params.push(`urgence=${this.urgenceFiltre}`);
}


if(params.length){
path+=`?${params.join('&')}`;
}


this.api.get<Dossier[]>(path).subscribe({

next:(data)=>{
this.dossiers.set(data);
this.loading.set(false);
},

error:()=>{
this.loading.set(false);
}

});

}



toggleProposal(idDossier:number){

if(this.proposalOpenFor()===idDossier){

this.proposalOpenFor.set(null);

}else{

this.proposalOpenFor.set(idDossier);
this.proposalMessage='';

}

}



envoyerProposition(idDossier:number){

console.log('ENVOI PROPOSITION',idDossier);


this.api.post(`/api/avocat/dossiers/${idDossier}/proposer`,{
message:this.proposalMessage
})
.subscribe({

next:()=>{

this.proposalSent.update(m=>({
...m,
[idDossier]:'Proposition envoyée !'
}));

this.proposalOpenFor.set(null);

},


error:(err)=>{

this.proposalError.update(m=>({
...m,
[idDossier]:err?.error?.message || 'Erreur lors de l’envoi.'
}));

}

});


}

}