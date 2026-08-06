import { Component, OnInit, signal, computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive, RouterOutlet, ActivatedRoute } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';

interface Thread{
id_dossier:number;
nom:string;
prenom:string;
dernier_message:string|null;
heure:string|null;
}

@Component({
selector:'app-avocat-messages-layout',
standalone:true,
imports:[
CommonModule,
FormsModule,
RouterLink,
RouterLinkActive,
RouterOutlet
],
templateUrl:'./avocat-messages-layout.html',
styleUrls:['./avocat-messages-layout.scss']
})
export class AvocatMessagesLayout implements OnInit{

threads=signal<Thread[]>([]);
loading=signal(true);
hasSelection=signal(false);
query=signal('');

filteredThreads=computed(()=>{
const q=this.query().trim().toLowerCase();

const list=[...this.threads()].sort((a,b)=>{
const da=a.heure?new Date(a.heure).getTime():0;
const db=b.heure?new Date(b.heure).getTime():0;
return db-da;
});

if(!q)return list;

return list.filter(t=>
`${t.prenom} ${t.nom}`.toLowerCase().includes(q)
);
});

constructor(
private api:Api,
private route:ActivatedRoute
){}

ngOnInit():void{
this.load();
this.hasSelection.set(!!this.route.firstChild);
this.route.url.subscribe(()=>{
this.hasSelection.set(!!this.route.firstChild);
});
}

load():void{

this.loading.set(true);

this.api.get<Thread[]>('/api/messages/threads').subscribe({

next:data=>{
this.threads.set(data);
this.loading.set(false);
},

error:err=>{
console.error(err);
this.loading.set(false);
}

});

}

initials(t:Thread):string{

const p=t.prenom? t.prenom.charAt(0):'';
const n=t.nom? t.nom.charAt(0):'';

return (p+n).toUpperCase();

}

formatHeure(heure:string|null):string{

if(!heure)return'';

const date=new Date(heure);
const today=new Date();

if(date.toDateString()===today.toDateString()){

return date.toLocaleTimeString('fr-FR',{
hour:'2-digit',
minute:'2-digit'
});

}

return date.toLocaleDateString('fr-FR',{
day:'2-digit',
month:'2-digit'
});

}

refresh(){
this.load();
}

}