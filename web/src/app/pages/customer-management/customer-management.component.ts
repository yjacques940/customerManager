import { Component, OnInit } from '@angular/core';
import {CustomerManagementService} from './shared/services/customer-management.service';
import {Customer} from './shared/models/customer';

@Component({
  selector: 'app-customer-management',
  templateUrl: './customer-management.component.html',
  styleUrls: ['./customer-management.component.scss']
})
export class CustomerManagementComponent implements OnInit {
customers: Customer[] = [];
displayedColumns: string[] = ['firstName', 'lastName', 'occupation'];
  constructor(private customerManagementService: CustomerManagementService) { }

  ngOnInit() {
    this.getCustomers();
  }

  private getCustomers() {
    this.customerManagementService.getAll()
      .subscribe(customers => this.customers = customers);
  }
}
