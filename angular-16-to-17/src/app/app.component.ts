import { Component } from '@angular/core';
import { RouterModule } from '@angular/router'; // MIGRATED: Angular 17 — standalone component needs RouterModule for router-outlet

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  standalone: true, // MIGRATED: Angular 17 — converted to standalone component
  imports: [RouterModule] // MIGRATED: Angular 17 — imported RouterModule for router directives used in template
})
export class AppComponent {
  title = 'Angular 16 Crud example';
}