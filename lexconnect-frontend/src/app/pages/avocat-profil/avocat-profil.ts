import { Component, OnInit, signal, computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { Api } from '../../services/api';

interface Dossier {
  id_dossier:number;
  motif:string;
}

interface Avis {
  id_avis:number;
  note:number;
  commentaire:string|null;
  date:string|null;
  citoyen:string;
}

@Component({
selector:'app-avocat-profil',
standalone:true,
imports:[CommonModule,FormsModule],
templateUrl:'./avocat-profil.html',
styleUrl:'./avocat-profil.scss',
})
export class AvocatProfil implements OnInit{

nom='';
prenom='';
email='';

specialites='';
bio='';
disponibilite='disponible';
horaire='';
numero_barre='';

idAvocat:number|null=null;

dossiers=signal<Dossier[]>([]);
selectedDossier:number|null=null;

loading=signal(true);
editing=signal(false);
savedMessage=signal('');

deleteConfirm=signal(false);

sentMessage=signal('');

isOwner=false;

avisListe=signal<Avis[]>([]);
avisLoading=signal(true);

avisMoyenne=computed(()=>{
  const liste=this.avisListe();
  if(liste.length===0)return 0;
  const total=liste.reduce((sum,a)=>sum+a.note,0);
  return Math.round((total/liste.length)*10)/10;
});

// FIX: signal au lieu d'une simple propriété, sinon le computed ne se recalcule jamais
avisVisibles=signal(3);

avisAffiches=computed(()=>this.avisListe().slice(0,this.avisVisibles()));

voirPlusAvis(){
this.avisVisibles.update(v=>v+5);
}


constructor(
private api:Api,
private route:ActivatedRoute
){}


ngOnInit(){

this.idAvocat=Number(this.route.snapshot.paramMap.get('id'));

if(this.idAvocat){

this.isOwner=false;

this.loadPublicProfil();

this.loadAvis(this.idAvocat);

}else{

this.isOwner=true;

this.load();

}

}



load(){

this.loading.set(true);

this.api.get<any>('/api/user')
.subscribe({

next:(u)=>{

this.nom=u.nom;
this.prenom=u.prenom;
this.email=u.email;

}

});


this.api.get<any>('/api/avocat/profil')
.subscribe({

next:(data)=>{

const p=data?.profil;

if(p){

this.specialites=p.specialites ?? '';
this.bio=p.bio ?? '';
this.disponibilite=p.disponibilite ?? 'disponible';
this.horaire=p.horaire ?? '';
this.numero_barre=p.numero_barre ?? '';

}

if(data?.id_avocat){

this.loadAvis(data.id_avocat);

}

this.loading.set(false);

},

error:()=>{

this.loading.set(false);

}

});

}




loadPublicProfil(){

this.loading.set(true);


this.api.get<any>(`/api/citoyen/avocats/${this.idAvocat}`)
.subscribe({

next:(a)=>{

this.nom=a.utilisateur.nom;
this.prenom=a.utilisateur.prenom;
this.email=a.utilisateur.email;

this.specialites=a.profil?.specialites ?? '';
this.bio=a.profil?.bio ?? '';
this.disponibilite=a.profil?.disponibilite ?? 'disponible';
this.horaire=a.profil?.horaire ?? '';
this.numero_barre=a.profil?.numero_barre ?? '';

this.loading.set(false);

}

});



this.api.get<Dossier[]>('/api/citoyen/dossiers')
.subscribe({

next:(data)=>{

this.dossiers.set(
data.filter((d:any)=>d.statut_dossier!=='cloture')
);

}

});


}




loadAvis(idAvocat:number){

this.avisLoading.set(true);

this.api.get<Avis[]>(`/api/avocats/${idAvocat}/avis`)
.subscribe({

next:(data)=>{

this.avisListe.set(data);

this.avisLoading.set(false);

},

error:()=>{

this.avisLoading.set(false);

}

});

}




get initials(){

return(
this.prenom.charAt(0)+
this.nom.charAt(0)
).toUpperCase();

}




modifier(){

this.editing.set(true);

}



annuler(){

this.editing.set(false);

this.load();

}




save(){

this.api.put('/api/avocat/profil',{

specialites:this.specialites,
bio:this.bio,
disponibilite:this.disponibilite,
horaire:this.horaire,
numero_barre:this.numero_barre

})
.subscribe({

next:()=>{

this.savedMessage.set(
'Profil mis à jour avec succès.'
);

this.editing.set(false);


setTimeout(()=>{

this.savedMessage.set('');

},3000);

}

});

}





envoyerDemande(){

if(!this.selectedDossier || !this.idAvocat)
return;


this.api.post('/api/citoyen/demandes',{

id_dossier:this.selectedDossier,
id_avocat:this.idAvocat

})
.subscribe({

next:()=>{

this.sentMessage.set(
'Demande envoyée avec succès.'
);

},

error:()=>{

this.sentMessage.set(
'Erreur lors de l’envoi.'
);

}

});


}





supprimerCompte(){


if(!this.deleteConfirm()){

this.deleteConfirm.set(true);

return;

}



this.api.delete('/api/profile')
.subscribe({

next:()=>{

localStorage.clear();

window.location.href='/';

}

});


}



}