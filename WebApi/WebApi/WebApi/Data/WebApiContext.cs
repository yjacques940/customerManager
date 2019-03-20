using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Cause.Core.DataLayerExtensions;
using Microsoft.EntityFrameworkCore;
using WebApi.Data;

namespace WebApi.Models
{
    public class WebApiContext : DbContext, IWebApiContext
    {
        public WebApiContext (DbContextOptions<WebApiContext> options)
            : base(options)
        {
        }

        public DbSet<Customer> Customers { get; set; }


        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.AddTableNameToPrimaryKey();
            modelBuilder.UseAutoSnakeCaseMapping();
            modelBuilder.UseTablePrefix("tbl_");
            this.UseAutoDetectedMappings(modelBuilder);
        }
    }
}
