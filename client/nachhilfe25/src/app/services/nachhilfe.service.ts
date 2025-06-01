import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {catchError, Observable, retry, throwError} from 'rxjs';
import {Category} from '../classes/category';
import {Lesson} from '../classes/lesson';
import {Appointment} from '../classes/appointment';
import {Booking} from '../classes/booking';

@Injectable({
  providedIn: 'root'
})
export class NachhilfeService {
  private api:string = 'http://nachhilfe25.s2210456033.student.kwmhgb.at/api';

  constructor(private http:HttpClient) {}

  // ------------------------------------------- general--------------------------------------------------
  getAllCategories():Observable<Category[]> {
    return this.http.get<Category[]>(`${this.api}/categories`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  getLessonsByID(id:number):Observable<Lesson[]> {
    return this.http.get<Lesson[]>(`${this.api}/categories/lessons/${id}`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  getLessonByID(lessonId:string):Observable<Lesson> {
    return this.http.get<Lesson>(`${this.api}/lessons/${lessonId}`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  // ------------------------------------------- tutor -----------------------------------------------------
  getCategoriesForTutor():Observable<Category[]> {
    return this.http.get<Category[]>(`${this.api}/tutor/categories`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  getLessonsForTutor(id:number):Observable<Lesson[]> {
    return this.http.get<Lesson[]>(`${this.api}/tutor/categories/lessons/${id}`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  getBookedLessonsForTutor():Observable<Booking[]> {
    return this.http.get<Booking[]>(`${this.api}/tutor/bookings/upcoming`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  getBookingRequestsForTutor():Observable<Booking[]> {
    return this.http.get<Booking[]>(`${this.api}/tutor/bookings/pending`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  getHistoryForTutor():Observable<Booking[]> {
    return this.http.get<Booking[]>(`${this.api}/tutor/bookings/finished`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  acceptBooking(bookingId:number):Observable<Booking> {
    return this.http.put<Booking>(`${this.api}/tutor/bookings/${bookingId}/accept`, {})
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  rejectBooking(bookingId:number):Observable<Booking> {
    return this.http.put<Booking>(`${this.api}/tutor/bookings/${bookingId}/reject`, {})
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  createLesson(lesson:Lesson):Observable<Lesson> {
    return this.http.post<Lesson>(`${this.api}/tutor/lessons`, lesson)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  updateLesson(lesson:Lesson):Observable<Lesson> {
    return this.http.put<Lesson>(`${this.api}/tutor/lessons/${lesson.id}`, lesson)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  deleteAppointment(id:number):Observable<Appointment> {
    return this.http.delete<Appointment>(`${this.api}/tutor/appointments/${id}`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  // ------------------------------------------- student -----------------------------------------------------
  sendBookingRequest(appointmentId: number, tutorId: number, comment: string): Observable<Booking> {
    return this.http.post<Booking>(`${this.api}/bookings`, {
      comment: comment,
      appointment_id: appointmentId,
      tutor_id: tutorId,
    })
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }
  getLessonsForStudent(id:number):Observable<Lesson[]> {
    return this.http.get<Lesson[]>(`${this.api}/student/categories/lessons/${id}`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  getBookedLessonsForStudent():Observable<Booking[]> {
    return this.http.get<Booking[]>(`${this.api}/student/bookings/upcoming`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  getBookingRequestsForStudent():Observable<Booking[]> {
    return this.http.get<Booking[]>(`${this.api}/student/bookings/pending`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  getHistoryForStudent():Observable<Booking[]> {
    return this.http.get<Booking[]>(`${this.api}/student/bookings/finished`)
      .pipe(retry(3))
      .pipe(catchError(this.errorHandler));
  }

  private errorHandler(error:Error | any): Observable<any> {
    return throwError(error)
  }
}
