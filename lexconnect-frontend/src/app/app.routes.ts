import { Routes } from '@angular/router';
import { Landing } from './pages/landing/landing';
import { RegisterCitoyen } from './pages/register-citoyen/register-citoyen';
import { RegisterAvocat } from './pages/register-avocat/register-avocat';
import { Login } from './pages/login/login';
import { Dashboard } from './pages/dashboard/dashboard';
import { CompteDesactive } from './pages/compte-desactive/compte-desactive';
import { Contact } from './pages/contact/contact';

import { CitoyenLayout } from './layouts/citoyen-layout/citoyen-layout';
import { CitoyenDashboard } from './pages/citoyen-dashboard/citoyen-dashboard';
import { DossiersListe } from './pages/dossiers-liste/dossiers-liste';
import { DossierForm } from './pages/dossier-form/dossier-form';
import { DossierDetail } from './pages/dossier-detail/dossier-detail';
import { ProfilCitoyen } from './pages/profil-citoyen/profil-citoyen';
import { RechercheAvocat } from './pages/recherche-avocat/recherche-avocat';
import { MessagesLayout } from './pages/messages-layout/messages-layout';
import { MessageThread } from './pages/message-thread/message-thread';

import { AvocatLayout } from './layouts/avocat-layout/avocat-layout';
import { AvocatDashboard } from './pages/avocat-dashboard/avocat-dashboard';
import { AvocatProfil } from './pages/avocat-profil/avocat-profil';
import { AvocatMessagesLayout } from './pages/avocat-messages-layout/avocat-messages-layout';
import { AvocatMessageThread } from './pages/avocat-message-thread/avocat-message-thread';

import { AdminLayout } from './layouts/admin-layout/admin-layout';
import { AdminDashboard } from './pages/admin-dashboard/admin-dashboard';
import { AdminUtilisateurs } from './pages/admin-utilisateurs/admin-utilisateurs';
import { AdminSupport } from './pages/admin-support/admin-support';
import { InscriptionEnAttente } from './pages/inscription-en-attente/inscription-en-attente';
import { AvocatRechercheDossiers } from './pages/avocat-recherche-dossiers/avocat-recherche-dossiers';

export const routes: Routes = [
  { path: '', component: Landing },
  { path: 'login', component: Login },
  { path: 'register/citoyen', component: RegisterCitoyen },
  { path: 'register/avocat', component: RegisterAvocat },
  { path: 'dashboard', component: Dashboard },
  { path: 'compte-desactive', component: CompteDesactive },
  { path: 'contact', component: Contact },
  { path: 'inscription-en-attente', component: InscriptionEnAttente },

  {
  path: 'dashboard/citoyen',
  component: CitoyenLayout,
  children: [
    { path: '', component: CitoyenDashboard },
    { path: 'profil', component: ProfilCitoyen },
    { path: 'avocats', component: RechercheAvocat },
    { path: 'avocats/:id', component: AvocatProfil },
    {
      path: 'messages',
      component: MessagesLayout,
      children: [
        { path: ':id', component: MessageThread },
      ],
    },
    { path: 'dossiers', component: DossiersListe },
    { path: 'dossiers/nouveau', component: DossierForm },
    { path: 'dossiers/:id', component: DossierDetail },
  ],
},

  {
    path: 'dashboard/avocat',
    component: AvocatLayout,
    children: [
      { path: '', component: AvocatDashboard },
      { path: 'profil', component: AvocatProfil },
      {
        path: 'messages',
        component: AvocatMessagesLayout,
        children: [
          { path: ':id', component: AvocatMessageThread },
        ],
      },
      { path: 'dossiers', component: AvocatRechercheDossiers },
    ],
  },

  {
    path: 'dashboard/admin',
    component: AdminLayout,
    children: [
      { path: '', component: AdminDashboard },
      { path: 'utilisateurs', component: AdminUtilisateurs },
      { path: 'support', component: AdminSupport },
    ],
  },
];