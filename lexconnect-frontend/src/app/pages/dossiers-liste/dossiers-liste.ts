import { Component,OnInit,signal,computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { Api } from '../../services/api';

interface Dossier{
id_dossier:number;
motif:string;
description:string;
statut_dossier:string;
niveau_urgence:number;
}

@Component({
selector:'app-dossiers-liste',
standalone:true,
imports:[CommonModule,RouterLink],
templateUrl:'./dossiers-liste.html',
styleUrl:'./dossiers-liste.scss'
})
export class DossiersListe implements OnInit{

dossiers=signal<Dossier[]>([]);
loading=signal(true);

filtre=signal<'tous'|'ouvert'|'en_cours'|'cloture'>('tous');

statutLabels:Record<string,string>={
ouvert:'En attente',
en_cours:'En cours',
cloture:'Clôturé'
};


filtered=computed(()=>{

const f=this.filtre();

let data=this.dossiers();

if(f!=='tous'){
data=data.filter(d=>d.statut_dossier===f);
}

return [...data].sort(
(a,b)=>b.niveau_urgence-a.niveau_urgence
);

});


constructor(private api:Api){}


ngOnInit(){

this.api.get<Dossier[]>('/api/citoyen/dossiers')
.subscribe({

next:data=>{

this.dossiers.set(data);
this.loading.set(false);

},

error:()=>this.loading.set(false)

});

}


setFiltre(f:'tous'|'ouvert'|'en_cours'|'cloture'){

this.filtre.set(f);

}

}