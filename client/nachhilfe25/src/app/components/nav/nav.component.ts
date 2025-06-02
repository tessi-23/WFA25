import {Component, computed, signal} from '@angular/core';
import {Menubar} from 'primeng/menubar';
import {MenuItem} from 'primeng/api';
import {Router, RouterLink} from '@angular/router';
import {AuthService} from '../../services/auth.service';

@Component({
  selector: 'bs-nav',
  imports: [
    Menubar,
    RouterLink
  ],
  templateUrl: './nav.component.html',
  styles: ``
})
export class NavComponent {
  constructor(private authService: AuthService, private router: Router) { }


  // Dynamisches Menü basierend auf dem Login-Status
  items = computed<MenuItem[]>(() =>
    this.authService.isLoggedIn() ? this.loggedInItems() : this.loggedOutItems()
  );

  private loggedInItems = signal<MenuItem[]>([
    { label: 'Courses', icon: 'pi pi-book', command: () => this.router.navigate(['/categories']) },
    { label: 'Upcoming Appointments', icon: 'pi pi-calendar', command: () => this.router.navigate(['/upcoming']) },
    { label: 'History', icon: 'pi pi-history', command: () => this.router.navigate(['/history']) },
    { label: 'Requests', icon: 'pi pi-bell', command: () => this.router.navigate(['/requests']) },
    { label: 'Logout', icon: 'pi pi-sign-out',
      command: () => {
        this.authService.logout("/categories");
      }
    }
  ]);

  private loggedOutItems = signal<MenuItem[]>([
    { label: 'Courses', icon: 'pi pi-book', command: () => this.router.navigate(['/categories']) },
    { label: 'Login', icon: 'pi pi-sign-in', command: () => this.router.navigate(['/login']) }
  ]);
}
