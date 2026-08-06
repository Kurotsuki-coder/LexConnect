import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { Api } from '../../services/api';


interface Thread {

id_dossier:number;

nom:string;

prenom:string;

dernier_message:string|null;

heure:string|null;

}



@Component({

selector:'app-messages-threads',

standalone:true,

imports:[CommonModule,RouterLink],

templateUrl:'./messages-threads.html',

styleUrl:'./messages-threads.scss',

})


export class MessagesThreads implements OnInit {


threads = signal<Thread[]>([]);

loading = signal(true);



constructor(private api:Api){}



ngOnInit(){


this.api.get<Thread[]>('/api/messages/threads')

.subscribe({

next:(data)=>{


console.log(data);


this.threads.set(data);


this.loading.set(false);


},


error:(err)=>{


console.error(err);


this.loading.set(false);


}


});


}




initials(t:Thread):string{


return (

t.prenom.charAt(0)+

t.nom.charAt(0)

).toUpperCase();


}



}