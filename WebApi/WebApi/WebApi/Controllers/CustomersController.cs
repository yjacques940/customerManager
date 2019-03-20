using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class CustomersController : BaseController<CustomerService,Customer>
    {
        public CustomersController(WebApiContext context, CustomerService service) : base(service)
        {
        }

        //// GET: api/Customers
        [HttpGet("getAll")]
        public ActionResult<IEnumerable<Customer>> GetCustomer()
        {
            // return await Context.Customer.ToListAsync();
            return Service.GetList();
        }

        [HttpGet("FullName/{id}")]
        public string GetCustomerFullName(int id)
        {
            return Service.GetCustomerFullName(id);
        }

        //// GET: api/Customers/5
        //[HttpGet("get/{id}")]
        //public async Task<ActionResult<Customer>> GetCustomer(int id)
        //{
        //    var customer = await Context.Customer.FindAsync(id);

        //    if (customer == null)
        //    {
        //        return NotFound();
        //    }

        //    return customer;
        //}

        ////// PUT: api/Customers/5
        ////[HttpPut("{id}")]
        ////public async Task<IActionResult> PutCustomer(int id, Customer customer)
        ////{
        ////    if (id != customer.Id)
        ////    {
        ////        return BadRequest();
        ////    }

        ////    Context.Entry(customer).State = EntityState.Modified;

        ////    try
        ////    {
        ////        await Context.SaveChangesAsync();
        ////    }
        ////    catch (DbUpdateConcurrencyException)
        ////    {
        ////        if (!CustomerExists(id))
        ////        {
        ////            return NotFound();
        ////        }
        ////        else
        ////        {
        ////            throw;
        ////        }
        ////    }

        ////    return NoContent();
        ////}

        //// POST: api/Customers
        //[HttpPost]
        //public async Task<ActionResult<Customer>> PostCustomer(Customer customer)
        //{
        //    Context.Customer.Add(customer);
        //    await Context.SaveChangesAsync();

        //    return CreatedAtAction("GetCustomer", new { id = customer.Id }, customer);
        //}

        //// DELETE: api/Customers/5
        //[HttpDelete("{id}")]
        //public async Task<ActionResult<Customer>> DeleteCustomer(int id)
        //{
        //   // var customer = await Context.Customer.FindAsync(id);
        //   // if (customer == null)
        //   // {
        //   //     return NotFound();
        //   // }

        //   //// Context.Customer.Remove(customer);
        //   //// await Context.SaveChangesAsync();

        //   // return customer;

        //}

        //private bool CustomerExists(int id)
        //{
        //   // return Context.Customer.Any(e => e.Id == id);
        //    return true;
        //}
    }
}
