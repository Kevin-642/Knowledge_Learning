import { Application } from 'stimulus';
import { definitionsFromContext } from 'stimulus/webpack-helpers';

// Créer une instance de l'application Stimulus
const app = Application.start();

// Enregistrer des contrôleurs (si tu en as)
const context = require.context('./controllers', true, /.js$/);
app.load(definitionsFromContext(context));