using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.EntityFrameworkCore;

namespace WebApi.Models
{
    public class WebApiContext : DbContext
    {
        public WebApiContext (DbContextOptions<WebApiContext> options)
            : base(options)
        {
        }

        public DbSet<Customer> Customer { get; set; }
    }
}
