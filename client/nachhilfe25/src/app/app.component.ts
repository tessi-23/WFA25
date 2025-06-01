import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import {ButtonModule} from 'primeng/button';
import {NavComponent} from './components/nav/nav.component';
import {Toast} from 'primeng/toast';
import {MessageService} from 'primeng/api';

@Component({
  selector: 'bs-root',
  imports: [
    RouterOutlet,
    ButtonModule,
    NavComponent,
    Toast
  ],
  providers: [MessageService],
  templateUrl: './app.component.html',
})
export class AppComponent {
  title = 'nachhilfe25';
}
