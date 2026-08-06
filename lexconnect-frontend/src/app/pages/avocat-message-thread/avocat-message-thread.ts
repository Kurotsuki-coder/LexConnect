import { Component, OnInit, signal, computed, ViewChild, ElementRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { Api } from '../../services/api';
import { environment } from '../../../environments/environment';

interface Message{
id_message:number;
contenu:string|null;
heure:string;
id_expediteur:number;
id_receveur:number;
chemin_fichier?:string|null;
nom_fichier?:string|null;
type_fichier?:string|null;
taille_fichier?:number|null;
}

@Component({
selector:'app-avocat-message-thread',
standalone:true,
imports:[
CommonModule,
FormsModule
],
templateUrl:'./avocat-message-thread.html',
styleUrls:['./avocat-message-thread.scss']
})
export class AvocatMessageThread implements OnInit{

messages=signal<Message[]>([]);
correspondantNom=signal('');
monId=signal(0);
loading=signal(true);
uploading=signal(false);
dossierFerme=signal(false);

nouveauMessage='';
apiUrl=environment.apiUrl;

private idDossier=0;
private idReceveur=0;

@ViewChild('bottom') bottom!:ElementRef;
@ViewChild('fileInput') fileInput!:ElementRef<HTMLInputElement>;

constructor(
private api:Api,
private route:ActivatedRoute,
private router:Router
){}

correspondantInitials=computed(()=>{

const nom=this.correspondantNom().trim();

if(!nom)return'?';

const parts=nom.split(' ');

if(parts.length>=2){
return(parts[0][0]+parts[1][0]).toUpperCase();
}

return nom.charAt(0).toUpperCase();

});

groupedMessages=computed(()=>{

const groupes:{date:string,messages:Message[]}[]=[];

for(const m of this.messages()){

const date=this.formatDateLabel(m.heure);

const dernier=groupes[groupes.length-1];

if(dernier && dernier.date===date){

dernier.messages.push(m);

}else{

groupes.push({
date,
messages:[m]
});

}

}

return groupes;

});

ngOnInit():void{

this.idDossier=Number(this.route.snapshot.paramMap.get('id'));

this.api.get<any>('/api/user').subscribe({

next:user=>{

this.monId.set(user.id_utilisateur);

},

error:err=>console.error(err)

});

this.load();

}

load():void{

this.loading.set(true);

this.api.get<any>(`/api/messages/${this.idDossier}`).subscribe({

next:data=>{

this.messages.set(data.messages??[]);

this.correspondantNom.set(data.correspondant??'Conversation');

this.dossierFerme.set(data.statut_dossier==='cloture');

if(data.receveur){

this.idReceveur=data.receveur.id_utilisateur;

}else if(data.messages.length){

const dernier=data.messages[data.messages.length-1];

this.idReceveur=
dernier.id_expediteur===this.monId()
?dernier.id_receveur
:dernier.id_expediteur;

}

this.loading.set(false);

this.scrollBottom();

},

error:err=>{

console.error(err);

this.loading.set(false);

}

});

}

envoyer(){

if(this.dossierFerme())return;

const texte=this.nouveauMessage.trim();

if(!texte)return;

const temp:Message={

id_message:Date.now(),

contenu:texte,

heure:new Date().toISOString(),

id_expediteur:this.monId(),

id_receveur:this.idReceveur

};

this.messages.update(list=>[...list,temp]);

this.nouveauMessage='';

this.scrollBottom();

this.api.post('/api/messages',{

id_dossier:this.idDossier,

id_receveur:this.idReceveur,

contenu:texte

}).subscribe({

next:()=>{

this.load();

},

error:err=>{

console.error(err);

this.messages.update(list=>

list.filter(m=>m.id_message!==temp.id_message)

);

if(err.status===403){

this.dossierFerme.set(true);

}

}

});

}

ouvrirSelecteurFichier(){

if(this.dossierFerme())return;

this.fileInput.nativeElement.click();

}

onFileSelected(event:Event){

if(this.dossierFerme())return;

const input=event.target as HTMLInputElement;

if(!input.files?.length)return;

const fichier=input.files[0];

if(fichier.size>10*1024*1024){

alert('Le fichier dépasse 10 Mo.');

input.value='';

return;

}

const formData=new FormData();

formData.append('id_dossier',String(this.idDossier));

formData.append('id_receveur',String(this.idReceveur));

formData.append('fichier',fichier);

this.uploading.set(true);

this.api.postFile('/api/messages/fichier',formData).subscribe({

next:()=>{

this.uploading.set(false);

input.value='';

this.load();

},

error:err=>{

console.error(err);

this.uploading.set(false);

input.value='';

if(err.status===403){

this.dossierFerme.set(true);

}else{

alert("Erreur lors de l'envoi du fichier.");

}

}

});

}

fileUrl(m:Message){

return`${this.apiUrl}/storage/${m.chemin_fichier}`;

}

isImage(m:Message){

return!!m.type_fichier && m.type_fichier.startsWith('image/');

}

formatTaille(bytes?:number|null){

if(!bytes)return'';

if(bytes<1024)return`${bytes} o`;

if(bytes<1024*1024)return`${(bytes/1024).toFixed(1)} Ko`;

return`${(bytes/(1024*1024)).toFixed(1)} Mo`;

}

formatDateLabel(date:string){

const d=new Date(date);

const today=new Date();

const yesterday=new Date();

yesterday.setDate(today.getDate()-1);

if(d.toDateString()===today.toDateString())return"Aujourd'hui";

if(d.toDateString()===yesterday.toDateString())return"Hier";

return d.toLocaleDateString('fr-FR',{

day:'numeric',

month:'long',

year:'numeric'

});

}

scrollBottom(){

setTimeout(()=>{

this.bottom?.nativeElement.scrollIntoView({

behavior:'smooth'

});

},100);

}

retour(){

this.router.navigate(['/dashboard/avocat/messages']);

}

}