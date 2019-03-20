using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
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
    }
}
