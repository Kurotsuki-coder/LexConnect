export interface Utilisateur {
  id_utilisateur: number;
  nom: string;
  prenom: string;
  email: string;
  telephone?: string;
  role: 'citoyen' | 'avocat' | 'admin';
  statut: string;
  region: string;
  citoyen?: { id_citoyen: number };
  avocat?: { id_avocat: number };
  admin?: { id_admin: number };
}