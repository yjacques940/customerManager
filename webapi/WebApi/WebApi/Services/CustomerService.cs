using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class CustomerService : BaseCrudService<Customer>
    {
        public CustomerService(IWebApiContext context) : base(context)
        {
        }

        public string GetCustomerFullName(int customerId)
        {
            var customer = Context.Customers.Where(c => c.Id == customerId);
            return customer.First().FirstName + " " + customer.First().LastName;
        }

        public int ChangeCustomerLastName(int id, string lastName)
        {
            var user = Context.Customers.Where(c => c.Id == id).First();
            if (user != null)
            {
                user.LastName = lastName;
                Context.Update(user);
            }

            Context.SaveChanges();
            return user.Id;
        }

        public int AddNewCustomer(Customer customer)
        {
            //ajouter validation si le user existe déjà
            //devra créer le dernier id automatiquement,
            //aller chercher le dernier +1
            if (customer != null)
            {
                Context.Add(customer);
            }

            Context.SaveChanges();
            //Renvoyer le id du customer qui a été assigné : customer.Id
            return customer.IdAddress;
        }

        public List<Customer> GetCustomersByPhone(string phone)
        {
            List<int> listPhoneIds = Context.PhoneNumbers.Where(c => c.Phone == phone).Select(c => c.Id).ToList();
            List<Customer> customers = Context.Customers.Where(c => listPhoneIds.Contains(c.Id)).ToList();
            return customers;
        }

        public List<Customer> GetCustomersByName(string name)
        {
            List<Customer> customers = Context.Customers.Where(c => c.LastName.Contains(name) || c.FirstName.Contains(name)).ToList();
            return customers;
        }
    }
}
