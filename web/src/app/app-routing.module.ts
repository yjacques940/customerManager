import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {CustomerComponent} from './customer/customer.component';
import {AppComponent} from './app.component';


const routes: Routes = [
  {
    path: '',
    component: AppComponent
  },
  { path: 'customers',
    component: CustomerComponent
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
