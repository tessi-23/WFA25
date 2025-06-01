import { Routes } from '@angular/router';
import {LoginComponent} from './pages/login/login.component';
import {CategoriesComponent} from './pages/categories/categories.component';
import {LessonsComponent} from './pages/lessons/lessons.component';
import {RequestsComponent} from './pages/requests/requests.component';
import {HistoryComponent} from './pages/history/history.component';
import {UpcomingLessonsComponent} from './pages/upcoming-lessons/upcoming-lessons.component';
import {LessonFormComponent} from './components/lesson-form/lesson-form.component';

export const routes: Routes = [
  { path:'', redirectTo:'categories', pathMatch:'full'},
  { path:'login', component:LoginComponent},
  { path:'categories', component:CategoriesComponent},
  { path:'categories/lessons/:categoryId', component:LessonsComponent},
  { path:'categories/lessons/:categoryId/form/:lessonId', component:LessonFormComponent},
  { path:'categories/lessons/:categoryId/form', component:LessonFormComponent},
  { path:'requests', component:RequestsComponent},
  { path:'history', component:HistoryComponent},
  { path:'upcoming', component:UpcomingLessonsComponent},
];
