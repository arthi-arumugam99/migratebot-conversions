// MIGRATED: Angular 17 — AppRoutingModule is a genuine configuration container (forRoot pattern).
// It is preserved as-is but exported routes are available for use with provideRouter() in bootstrapApplication().
// TODO: Manual review needed — if AppModule is migrated to bootstrapApplication(), replace this module
// with provideRouter(routes) in main.ts and delete this file.

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { TutorialsListComponent } from './components/tutorials-list/tutorials-list.component';
import { TutorialDetailsComponent } from './components/tutorial-details/tutorial-details.component';
import { AddTutorialComponent } from './components/add-tutorial/add-tutorial.component';

export const routes: Routes = [ // MIGRATED: Angular 17 — exported for use with provideRouter() if bootstrapApplication() is adopted
  { path: '', redirectTo: 'tutorials', pathMatch: 'full' },
  { path: 'tutorials', component: TutorialsListComponent },
  { path: 'tutorials/:id', component: TutorialDetailsComponent },
  { path: 'add', component: AddTutorialComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }