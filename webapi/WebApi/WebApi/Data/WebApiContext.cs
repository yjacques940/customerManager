using Cause.Core.DataLayerExtensions;
using Microsoft.EntityFrameworkCore;
using WebApi.Models;

namespace WebApi.Data
{
    public class WebApiContext : DbContext, IWebApiContext
    {
        public WebApiContext(DbContextOptions<WebApiContext> options)
            : base(options)
        {
        }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.AddTableNameToPrimaryKey();
            modelBuilder.UseAutoSnakeCaseMapping();
            modelBuilder.UseTablePrefix("tbl_");
            this.UseAutoDetectedMappings(modelBuilder);
        }

        public DbSet<Customer> Customers { get; set; }
        public DbSet<Appointment> Appointments { get; set; }
        public DbSet<PhoneNumber> PhoneNumbers { get; set; }
        public DbSet<User> Users { get; set; }
        public DbSet<PhoneType> PhoneTypes { get; set; }
        public DbSet<Permission> Permissions { get; set; }
        public DbSet<Address> Addresses { get; set; }
        public DbSet<State> States { get; set; }
        public DbSet<TimeSlot> TimeSlots { get; set; }
        public DbSet<ActionToken> ActionTokens { get; set; }
        public DbSet<Question> Questions { get; set; }
        public DbSet<Response> Responses { get; set; }
        public DbSet<FollowUp> FollowUps { get; set; }
        public DbSet<DiaporamaImage> DiaporamaImages { get; set; }
    }
}
