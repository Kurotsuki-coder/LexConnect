import { Component,OnInit,signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { Api } from '../../../services/api';

interface Message{
id_message:number;
id_expediteur:number;
id_receveur:number;
contenu:string;
heure:string;
statut_message:string;
}

interface Conversation{
id_utilisateur:number;
nom:string;
prenom:string;
}

@Component({
selector:'app-chat',
standalone:true,
imports:[
CommonModule,
FormsModule
],
templateUrl:'./chat.html',
styleUrl:'./chat.scss'
})
export class Chat implements OnInit{

messages=signal<Message[]>([]);
correspondant=signal<Conversation|null>(null);
loading=signal(true);

message='';

private idUtilisateur!:string;

constructor(
private api:Api,
private route:ActivatedRoute
){}

ngOnInit(){

this.idUtilisateur=this.route.snapshot.paramMap.get('id')!;
this.load();

}


load(){

this.loading.set(true);

this.api.get<any>(`/api/messages/${this.idUtilisateur}`)
.subscribe({

next:(data)=>{

this.messages.set(data.messages);
this.correspondant.set(data.correspondant);
this.loading.set(false);

},

error:()=>{

this.loading.set(false);

}

});

}


envoyer(){

if(!this.message.trim()) return;


this.api.post<Message>('/api/messages',{

id_receveur:this.idUtilisateur,
contenu:this.message

})
.subscribe({

next:(data)=>{

this.messages.update(list=>[...list,data]);
this.message='';

}

});

}

}