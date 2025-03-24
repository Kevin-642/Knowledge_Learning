import { Application } from 'stimulus';
import { definitionsFromContext } from 'stimulus/webpack-helpers';

// Créer une instance de l'application Stimulus
const app = Application.start();

// Charger tous les contrôleurs depuis le dossier 'controllers'
const context = require.context('./controllers', true, /.js$/);
app.load(definitionsFromContext(context));

console.log("Stimulus application started!");