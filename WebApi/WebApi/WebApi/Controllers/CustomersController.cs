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

        [HttpGet("getAll")]
        public ActionResult<IEnumerable<Customer>> GetCustomer()
        {
            return Service.GetList();
        }

        [HttpGet("FullName/{id}")]
        public string GetCustomerFullName(int id)
        {
            return Service.GetCustomerFullName(id);
        }

        [HttpPut("{customer}")]
        public ActionResult AddNewCustomerObject(Customer customer)
        {
            return Ok(Service.AddNewCustomer(customer));
        }

        [HttpPut("changeCustomerLastName/{id}/{lastName}")]
        public ActionResult UpdateCustomerLastName(int id, string lastName)
        {
            return Ok(Service.ChangeCustomerLastName(id,lastName));
        }

        [HttpDelete("Delete/{id}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public ActionResult DeleteCustomer(int id)
        {
            if(Service.Remove(id))
                 return NoContent();

            return BadRequest();
        }
    }
}
