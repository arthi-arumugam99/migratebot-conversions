// MIGRATED: Angular 17 — AppModule replaced with bootstrapApplication() pattern
// TODO: Manual review needed — Update main.ts to use bootstrapApplication(AppComponent, { providers: [...] })
// TODO: Manual review needed — Each component (AppComponent, AddTutorialComponent, TutorialDetailsComponent, TutorialsListComponent) needs standalone: true added to its @Component decorator
// TODO: Manual review needed — Each component needs its own imports array with the directives/modules it uses (FormsModule, RouterModule, CommonModule, etc.)
// TODO: Manual review needed — Delete this file once main.ts is updated to use bootstrapApplication()

import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { AddTutorialComponent } from './components/add-tutorial/add-tutorial.component';
import { TutorialDetailsComponent } from './components/tutorial-details/tutorial-details.component';
import { TutorialsListComponent } from './components/tutorials-list/tutorials-list.component';

// MIGRATED: Angular 17 — All declared components should become standalone.
// Once each component has standalone: true, remove them from declarations[] and this NgModule.
// Replace this NgModule entirely with bootstrapApplication() in main.ts:
//
// import { bootstrapApplication } from '@angular/platform-browser';
// import { provideRouter } from '@angular/router';
// import { provideHttpClient } from '@angular/common/http';
// import { routes } from './app-routing.module'; // or wherever routes are defined
// import { AppComponent } from './app/app.component';
//
// bootstrapApplication(AppComponent, {
//   providers: [
//     provideRouter(routes),      // MIGRATED: Angular 17 — replaces RouterModule.forRoot(routes)
//     provideHttpClient(),         // MIGRATED: Angular 17 — replaces HttpClientModule
//   ]
// });

@NgModule({
  declarations: [
    // TODO: Manual review needed — Remove each component from here after adding standalone: true to it
    AppComponent,
    AddTutorialComponent,
    TutorialDetailsComponent,
    TutorialsListComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    HttpClientModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }