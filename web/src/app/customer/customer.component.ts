import { Component, OnInit } from '@angular/core';
import {CustomerService} from './service/customer.service';
import {Customer} from './model/customer';

@Component({
  selector: 'app-customer',
  templateUrl: './customer.component.html',
  styleUrls: ['./customer.component.scss']
})
export class CustomerComponent implements OnInit {
customers: Customer[] = [];
  constructor(private customerService: CustomerService) { }

  ngOnInit() {
    this.getCustomers();
  }

  getCustomers() {
    this.customerService.get().subscribe(customers => {
      this.customers = customers;
      console.log(customers);
    }, error => console.log(error));
  }
}
