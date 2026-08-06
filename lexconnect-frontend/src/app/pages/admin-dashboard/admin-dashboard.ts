import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Api } from '../../services/api';
import { MotifModal } from '../../components/motif-modal/motif-modal';

interface Profil{
  numero_barre:string|null;
}

interface Utilisateur{
  id_utilisateur:number;
  nom:string;
  prenom:string;
  email:string;
  telephone:string|null;
  region:string;
  statut:string;
  avocat?:{
    profil:Profil|null;
  }|null;
}

interface Stats{
  total_citoyens:number;
  total_avocats:number;
  avocats_en_attente:number;
  comptes_suspendus:number;
}

@Component({
  selector:'app-admin-dashboard',
  standalone:true,
  imports:[CommonModule,MotifModal],
  templateUrl:'./admin-dashboard.html',
  styleUrl:'./admin-dashboard.scss'
})
export class AdminDashboard implements OnInit{

  stats=signal<Stats>({total_citoyens:0,total_avocats:0,avocats_en_attente:0,comptes_suspendus:0});
  avocatsEnAttente=signal<Utilisateur[]>([]);
  loading=signal(true);
  expandedId=signal<number|null>(null);
  modalVisible=signal(false);
  private pendingUserId:number|null=null;

  constructor(private api:Api){}

  ngOnInit(){
    this.loadStats();
    this.loadAvocatsEnAttente();
  }

  loadStats(){
    this.api.get<Stats>('/api/admin/stats').subscribe({
      next:s=>this.stats.set(s)
    });
  }

  loadAvocatsEnAttente(){
    this.loading.set(true);
    this.api.get<Utilisateur[]>('/api/admin/avocats-en-attente').subscribe({
      next:data=>{
        this.avocatsEnAttente.set(data);
        this.loading.set(false);
      },
      error:()=>this.loading.set(false)
    });
  }

  toggleDetail(id:number){
    this.expandedId.set(this.expandedId()===id?null:id);
  }

  numeroBarre(u:Utilisateur):string{
    return u.avocat?.profil?.numero_barre||'Non renseigné';
  }

  valider(id:number){
    this.api.patch(`/api/admin/utilisateurs/${id}/valider`,{}).subscribe({
      next:()=>{
        this.loadAvocatsEnAttente();
        this.loadStats();
      }
    });
  }

  refuser(id:number){
    this.pendingUserId=id;
    this.modalVisible.set(true);
  }

  onModalConfirm(motif:string){
    if(!this.pendingUserId)return;
    this.api.patch(`/api/admin/utilisateurs/${this.pendingUserId}/refuser`,{motif}).subscribe({
      next:()=>{
        this.loadAvocatsEnAttente();
        this.loadStats();
      }
    });
    this.closeModal();
  }

  onModalCancel(){
    this.closeModal();
  }

  private closeModal(){
    this.modalVisible.set(false);
    this.pendingUserId=null;
  }

  initials(u:Utilisateur):string{
    return `${u.prenom.charAt(0)}${u.nom.charAt(0)}`.toUpperCase();
  }
}