import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Api } from '../../services/api';

interface Demande {
  id_demande:number;
  id_dossier:number;
  message:string|null;
  statut_demande:string;

  dossier:{
    id_dossier:number;
    motif:string;
    description:string;
    budget:number|null;
    niveau_urgence:number;
    statut_dossier:string;

    citoyen:{
      utilisateur:{
        nom:string;
        prenom:string;
      }
    }
  };
}

interface Stats{
  demandes_en_attente:number;
  dossiers_en_cours:number;
  dossiers_resolus:number;
  note_moyenne:number;
  nombre_avis:number;
}

@Component({
  selector:'app-avocat-dashboard',
  standalone:true,
  imports:[CommonModule],
  templateUrl:'./avocat-dashboard.html',
  styleUrl:'./avocat-dashboard.scss'
})
export class AvocatDashboard implements OnInit{

  demandes = signal<Demande[]>([]);

  stats = signal<Stats>({
    demandes_en_attente:0,
    dossiers_en_cours:0,
    dossiers_resolus:0,
    note_moyenne:0,
    nombre_avis:0
  });

  loading = signal(true);

  demandeOuverte = signal<number|null>(null);

  utilisateur = signal<any>(null);

  constructor(private api:Api){}

  ngOnInit():void{

    this.loadUser();

    this.loadStats();

    this.loadDemandes();

  }

  /**
   * Utilisateur connecté
   */
  loadUser(){

    this.api.get<any>('/api/user')
    .subscribe({
      next:(u)=>this.utilisateur.set(u)
    });

  }

  /**
   * Prénom affiché
   */
  prenom(){

    return this.utilisateur()?.prenom || 'Maître';

  }

  /**
   * Date du jour
   */
  today(){

    return new Date().toLocaleDateString('fr-FR',{
      weekday:'long',
      day:'numeric',
      month:'long',
      year:'numeric'
    });

  }

  /**
   * Statistiques
   */
  loadStats(){

    this.api.get<Stats>('/api/avocat/stats')
    .subscribe({
      next:(s)=>this.stats.set(s)
    });

  }

  /**
   * Demandes
   */
  loadDemandes(){

    this.loading.set(true);

    this.api.get<Demande[]>('/api/avocat/demandes')
    .subscribe({

      next:(data)=>{

        this.demandes.set(data);

        this.loading.set(false);

      },

      error:()=>{

        this.loading.set(false);

      }

    });

  }

  /**
   * Ouvrir/Fermer les détails
   */
  voirPlus(id:number){

    this.demandeOuverte.update(v=>v===id?null:id);

  }

  /**
   * Accepter
   */
  accepter(id:number){

    this.api.patch(`/api/avocat/demandes/${id}/accepter`,{})
    .subscribe({

      next:()=>{

        this.loadDemandes();

        this.loadStats();

      }

    });

  }

  /**
   * Refuser
   */
  refuser(id:number){

    if(!confirm('Refuser cette demande ?')) return;

    this.api.patch(`/api/avocat/demandes/${id}/refuser`,{})
    .subscribe({

      next:()=>{

        this.loadDemandes();

        this.loadStats();

      }

    });

  }

  /**
   * Résoudre
   */
  resoudre(id:number){

    if(!confirm('Marquer ce dossier comme résolu ?')) return;

    this.api.patch(`/api/avocat/dossiers/${id}/resoudre`,{})
    .subscribe({

      next:()=>{

        this.loadDemandes();

        this.loadStats();

      }

    });

  }

  /**
   * Initiales citoyen
   */
  initials(d:Demande){

    const u = d.dossier.citoyen.utilisateur;

    return `${u.prenom.charAt(0)}${u.nom.charAt(0)}`.toUpperCase();

  }

  /**
   * Texte urgence
   */
  urgence(n:number){

    switch(n){

      case 1:
        return 'Faible';

      case 2:
        return 'Normale';

      case 3:
        return 'Urgente';

      default:
        return 'Critique';

    }

  }

}