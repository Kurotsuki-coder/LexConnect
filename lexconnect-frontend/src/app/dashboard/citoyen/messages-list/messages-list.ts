import { Component,OnInit,signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { Api } from '../../../services/api';

interface Thread{
id_utilisateur:number;
nom:string;
prenom:string;
dernier_message:string|null;
heure:string|null;
}

@Component({
selector:'app-messages-list',
standalone:true,
imports:[CommonModule,RouterLink],
templateUrl:'./messages-list.html',
styleUrl:'./messages-list.scss'
})
export class MessagesList implements OnInit{

threads=signal<Thread[]>([]);
loading=signal(true);

constructor(private api:Api){}

ngOnInit(){
this.load();
}

load(){

this.api.get<Thread[]>('/api/messages/threads')
.subscribe({

next:(data:Thread[])=>{
this.threads.set(data);
this.loading.set(false);
},

error:(err:any)=>{
console.error(err);
this.loading.set(false);
}

});

}

}