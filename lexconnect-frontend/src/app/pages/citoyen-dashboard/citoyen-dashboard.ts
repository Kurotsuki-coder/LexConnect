import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink, NavigationEnd } from '@angular/router';
import { filter } from 'rxjs';
import { Api } from '../../services/api';

interface Dossier {
  id_dossier:number;
  motif:string;
  description:string;
  statut_dossier:string;
  niveau_urgence:number;
}

interface Stats {
  actifs:number;
  urgents:number;
  demandes_acceptees:number;
  messages:number;
}

@Component({
  selector:'app-citoyen-dashboard',
  standalone:true,
  imports:[CommonModule,RouterLink],
  templateUrl:'./citoyen-dashboard.html',
  styleUrl:'./citoyen-dashboard.scss'
})
export class CitoyenDashboard implements OnInit {

  dossiers=signal<Dossier[]>([]);
  stats=signal<Stats>({
    actifs:0,
    urgents:0,
    demandes_acceptees:0,
    messages:0
  });

  prenom=signal('');
  loading=signal(true);

  statutLabels:Record<string,string>={
    ouvert:'En attente',
    en_cours:'En cours',
    cloture:'Clôturé'
  };

  constructor(
    private api:Api,
    private router:Router
  ){}

  ngOnInit(){

    this.api.get<any>('/api/user')
    .subscribe({
      next:u=>this.prenom.set(u.prenom)
    });

    this.loadDossiers();
    this.loadStats();

    this.router.events
    .pipe(filter(e=>e instanceof NavigationEnd))
    .subscribe(()=>{
      if(this.router.url==='/dashboard/citoyen'){
        this.loadDossiers();
        this.loadStats();
      }
    });
  }


  today(){
    return new Date().toLocaleDateString(
      'fr-FR',
      {
        weekday:'long',
        day:'numeric',
        month:'long',
        year:'numeric'
      }
    );
  }


  loadDossiers(){

    this.loading.set(true);

    this.api.get<Dossier[]>('/api/citoyen/dossiers')
    .subscribe({

      next:data=>{

        const tri=data.sort(
          (a,b)=>b.niveau_urgence-a.niveau_urgence
        );

        this.dossiers.set(tri);
        this.loading.set(false);

      },

      error:()=>this.loading.set(false)

    });
  }


  loadStats(){

    this.api.get<Stats>('/api/citoyen/dossiers-stats')
    .subscribe({

      next:s=>{

        this.stats.set({

          actifs:s.actifs ?? 0,
          urgents:s.urgents ?? 0,
          demandes_acceptees:s.demandes_acceptees ?? 0,
          messages:s.messages ?? 0

        });

      }

    });

  }

}