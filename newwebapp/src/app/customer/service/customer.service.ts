import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Customer} from '../model/customer';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class CustomerService {

  constructor(private httpClient: HttpClient) { }

  get(): Observable<Customer[]> {
    const url = `http://localhost/api/Customers/GetAll`;
    return this.httpClient.get<Customer[]>(url);
  }
}
