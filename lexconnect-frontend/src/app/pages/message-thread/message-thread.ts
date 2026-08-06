import { Component, OnInit, signal, computed, ViewChild, ElementRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { Api } from '../../services/api';
import { environment } from '../../../environments/environment';

interface Message {
  id_message: number;
  contenu: string | null;
  heure: string;
  id_expediteur: number;
  id_receveur: number;
  chemin_fichier?: string | null;
  nom_fichier?: string | null;
  type_fichier?: string | null;
  taille_fichier?: number | null;
}

@Component({
  selector: 'app-message-thread',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './message-thread.html',
  styleUrl: './message-thread.scss',
})
export class MessageThread implements OnInit {
  messages = signal<Message[]>([]);
  correspondantNom = signal('');
  monId = signal<number>(0);
  loading = signal(true);
  uploading = signal(false);
  dossierFerme = signal(false);

  nouveauMessage = '';
  apiUrl = environment.apiUrl;

  private idDossier: number = 0;
  private idReceveur: number = 0;

  @ViewChild('bottom') bottom!: ElementRef;
  @ViewChild('fileInput') fileInput!: ElementRef<HTMLInputElement>;

  correspondantInitials = computed(() => {
    const nom = this.correspondantNom().trim();
    const parts = nom.split(' ');
    if (parts.length >= 2) return (parts[0].charAt(0) + parts[1].charAt(0)).toUpperCase();
    return nom.charAt(0).toUpperCase();
  });

  groupedMessages = computed(() => {
    const groups: { date: string; messages: Message[] }[] = [];
    for (const m of this.messages()) {
      const dateLabel = this.formatDateLabel(m.heure);
      const lastGroup = groups[groups.length - 1];
      if (lastGroup && lastGroup.date === dateLabel) {
        lastGroup.messages.push(m);
      } else {
        groups.push({ date: dateLabel, messages: [m] });
      }
    }
    return groups;
  });

  constructor(private api: Api, private route: ActivatedRoute, private router: Router) {}

  ngOnInit() {
    this.idDossier = Number(this.route.snapshot.paramMap.get('id'));

    this.api.get<any>('/api/user').subscribe({
      next: (u) => this.monId.set(u.id_utilisateur),
      error: (err) => console.error(err),
    });

    this.load();
  }

  load() {
    this.loading.set(true);

    this.api.get<any>(`/api/messages/${this.idDossier}`).subscribe({
      next: (data) => {
        this.messages.set(data.messages ?? []);
        this.correspondantNom.set(data.correspondant ?? 'Conversation');
        this.dossierFerme.set(data.statut_dossier === 'cloture');

        if (data.receveur) {
          this.idReceveur = data.receveur.id_utilisateur;
        } else if (data.messages.length > 0) {
          const dernier = data.messages[data.messages.length - 1];
          this.idReceveur = dernier.id_expediteur === this.monId() ? dernier.id_receveur : dernier.id_expediteur;
        }

        this.loading.set(false);
        this.scrollBottom();
      },
      error: (err) => {
        console.error(err);
        this.loading.set(false);
      },
    });
  }

  envoyer() {
    if (this.dossierFerme()) return;

    const texte = this.nouveauMessage.trim();
    if (!texte) return;

    const messageTemp: Message = {
      id_message: Date.now(),
      contenu: texte,
      heure: new Date().toISOString(),
      id_expediteur: this.monId(),
      id_receveur: this.idReceveur,
    };

    this.messages.update((messages) => [...messages, messageTemp]);
    this.nouveauMessage = '';
    this.scrollBottom();

    this.api.post('/api/messages', { id_dossier: this.idDossier, id_receveur: this.idReceveur, contenu: texte }).subscribe({
      next: () => console.log('Message envoyé'),
      error: (err) => {
        console.error(err);
        this.messages.update((messages) => messages.filter((m) => m.id_message !== messageTemp.id_message));

        if (err.status === 403) {
          this.dossierFerme.set(true);
        }
      },
    });
  }

  ouvrirSelecteurFichier() {
    if (this.dossierFerme()) return;
    this.fileInput.nativeElement.click();
  }

  onFileSelected(event: Event) {
    if (this.dossierFerme()) return;

    const input = event.target as HTMLInputElement;
    if (!input.files || input.files.length === 0) return;

    const fichier = input.files[0];

    if (fichier.size > 10 * 1024 * 1024) {
      alert('Le fichier est trop volumineux (10 Mo maximum).');
      input.value = '';
      return;
    }

    const formData = new FormData();
    formData.append('id_dossier', String(this.idDossier));
    formData.append('id_receveur', String(this.idReceveur));
    formData.append('fichier', fichier);

    this.uploading.set(true);

    this.api.postFile<Message>('/api/messages/fichier', formData).subscribe({
      next: () => {
        this.uploading.set(false);
        input.value = '';
        this.load();
      },
      error: (err) => {
        console.error(err);
        this.uploading.set(false);
        input.value = '';

        if (err.status === 403) {
          this.dossierFerme.set(true);
        } else {
          alert("Erreur lors de l'envoi du fichier.");
        }
      },
    });
  }

  fileUrl(m: Message): string {
    return `${this.apiUrl}/storage/${m.chemin_fichier}`;
  }

  isImage(m: Message): boolean {
    return !!m.type_fichier && m.type_fichier.startsWith('image/');
  }

  formatTaille(bytes?: number | null): string {
    if (!bytes) return '';
    if (bytes < 1024) return `${bytes} o`;
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} Ko`;
    return `${(bytes / (1024 * 1024)).toFixed(1)} Mo`;
  }

  private formatDateLabel(heure: string): string {
    const date = new Date(heure);
    const today = new Date();
    const yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);

    if (date.toDateString() === today.toDateString()) return "Aujourd'hui";
    if (date.toDateString() === yesterday.toDateString()) return 'Hier';
    return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
  }

  scrollBottom() {
    setTimeout(() => {
      this.bottom?.nativeElement.scrollIntoView({ behavior: 'smooth' });
    }, 100);
  }

  retour() {
    this.router.navigate(['/dashboard/citoyen/messages']);
  }
}