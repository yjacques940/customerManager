import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {Customer} from '../models/customer';

@Injectable({
  providedIn: 'root'
})
export class CustomerManagementService {

  constructor(private httpClient: HttpClient) { }

  getAll(): Observable<Customer[]> {
    const url = `http://localhost:8080/api/Customers/GetAll`
    return this.httpClient.get<Customer[]>(url);
  }
}
