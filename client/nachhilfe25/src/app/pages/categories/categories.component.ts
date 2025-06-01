import {Component, inject, OnInit, signal} from '@angular/core';
import {DataViewModule} from 'primeng/dataview';
import {Category} from '../../classes/category';
import {NachhilfeService} from '../../services/nachhilfe.service';
import {Listbox} from 'primeng/listbox';
import {FormsModule} from '@angular/forms';
import {Router} from '@angular/router';
import {NgClass, NgForOf} from '@angular/common';
import {AuthService} from '../../services/auth.service';

@Component({
  selector: 'bs-categories',
  imports: [DataViewModule, Listbox, FormsModule, NgForOf, NgClass],
  templateUrl: './categories.component.html',
})
export class CategoriesComponent implements OnInit{
  categories = signal<Category[]>([]);
  private nachhilfeService = inject(NachhilfeService);
  private authService = inject(AuthService);
  private router = inject(Router);

ngOnInit() {
  const role = this.authService.getRole();
  if (role === 'tutor') {
    this.nachhilfeService.getCategoriesForTutor().subscribe(res => {
      this.categories.set(res);
    });
  } else {
    this.nachhilfeService.getAllCategories().subscribe(res => {
      this.categories.set(res);
    });
  }
}

  goToLessons(categoryId: number) {
    this.router.navigate(['categories/lessons/', categoryId]);
  }
}
